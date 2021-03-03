<?php

namespace App\Jobs;

use App\Support\Dto\Object\Request as RequestDto;
use App\Support\Dto\Object\Response as ResponseDto;
use App\Support\Dto\Object\Webhook as WebhookDto;
use App\Support\Headers as HeadersHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Imtigger\LaravelJobStatus\Trackable;
use GuzzleHttp\RequestOptions as GuzzleRequestOptions;

class ProcessRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    private const REQUEST_TIMEOUT = 20;

    /**
     * @var array
     */
    private array $request;

    /**
     * ProcessRequestJob constructor.
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->prepareStatus();

        $this->request = $request;

        $this->setInput($this->request);
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $requestDto = new RequestDto($this->request);
        $response   = Http::send(
            $requestDto->getMethod(),
            $requestDto->getUri(),
            $this->getRequestOptions($requestDto)
        );

        if ($response->serverError()) {
            $response->throw();
        }

        $responseDto = (new ResponseDto())
            ->setStatusCode($response->status())
            ->setBody($response->body())
            ->setHeaders(HeadersHelper::process($response->headers()));

        $this->setOutput($responseDto->toArray());

        $this->processWebhooks($requestDto, $responseDto);
    }

    /**
     * @param RequestDto $requestDto
     * @return array
     */
    private function getRequestOptions(RequestDto $requestDto): array
    {
        $options = [GuzzleRequestOptions::TIMEOUT => self::REQUEST_TIMEOUT];

        if ($queryParams = $requestDto->getQueryParams()) {
            $options[GuzzleRequestOptions::QUERY] = $queryParams;
        }

        if ($formParams = $requestDto->getFormParams()) {
            $options[GuzzleRequestOptions::FORM_PARAMS] = $formParams;
        } elseif ($body = $requestDto->getBody()) {
            $options[GuzzleRequestOptions::BODY] = $body;
        }

        if ($headers = $requestDto->getHeaders()) {
            $options[GuzzleRequestOptions::HEADERS] = $headers;
        }

        return $options;
    }

    /**
     * @param RequestDto  $requestDto
     * @param ResponseDto $responseDto
     * @return array
     */
    private function processWebhooks(RequestDto $requestDto, ResponseDto $responseDto): array
    {
        $jobStatusIds = [];

        if (!$webHooks = $requestDto->getWebHooks()) {
            return $jobStatusIds;
        }

        foreach ($webHooks as $webHookData) {
            $webhookDto = new WebhookDto($webHookData);
            $job        = (new ProcessWebhookJob([
                ProcessWebhookJob::SECTION_WEBHOOK  => $webhookDto->toArray(),
                ProcessWebhookJob::SECTION_RESPONSE => $responseDto->toArray()
            ]))->onQueue(JobQueue::WEBHOOKS);

            dispatch($job);

            $jobStatusIds[] = $job->getJobStatusId();
        }

        return $jobStatusIds;
    }
}
