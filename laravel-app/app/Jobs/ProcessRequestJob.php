<?php

namespace App\Jobs;

use App\Models\Response as ResponseModel;
use App\Support\Dto\Forge\Request as RequestDtoForge;
use App\Support\Dto\Object\Request as RequestDto;
use App\Support\Dto\Object\Response as ResponseDto;
use App\Support\Dto\Object\Webhook as WebhookDto;
use App\Support\Headers;
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

        try {

            $response = Http::send(
                $requestDto->getMethod(),
                $requestDto->getUri(),
                $this->getRequestOptions($requestDto)
            );
        } catch (\Exception $e) {
            $this->fail($e);
            return;
        }

        $responseDto = (new ResponseDto())
            ->setStatusCode($response->status())
            ->setBody($response->body())
            ->setHeaders(Headers::process($response->headers()));

        $this->saveResponse($responseDto);

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
     * @param RequestDto $requestDto
     * @param ResponseDto $responseDto
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function processWebhooks(RequestDto $requestDto, ResponseDto $responseDto)
    {
        if (!$webHooks = $requestDto->getWebHooks()) {
            return;
        }

        $response = $responseDto->toArray();

        foreach ($webHooks as $webHookData) {
            $webhookDto = new WebhookDto($webHookData);
            $job        = (new ProcessWebhookJob(['webhook' => $webhookDto->toArray(), 'response' => $response]))->onQueue('webhooks');

            dispatch($job);
        }
    }

    /**
     * @param ResponseDto $responseDto
     */
    private function saveResponse(ResponseDto $responseDto)
    {
        $responseModel                = new ResponseModel();
        $responseModel->job_status_id = $this->getJobStatusId();
        $responseModel->response      = $responseDto->toArray();

        $responseModel->save();
    }
}
