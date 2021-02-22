<?php

namespace App\Jobs;

use App\Support\Dto\Object\Response as ResponseDto;
use App\Support\Dto\Object\Webhook as WebhookDto;
use App\Support\Headers;
use GuzzleHttp\RequestOptions as GuzzleRequestOptions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Imtigger\LaravelJobStatus\Trackable;

class ProcessWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    private const REQUEST_TIMEOUT = 20;

    /**
     * @var array
     */
    private array $payload;

    /**
     * Create a new job instance.
     * @param array $payload
     * @return void
     */
    public function __construct(array $payload)
    {
        $this->prepareStatus();

        $this->payload = $payload;

        $this->setInput($this->payload);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $responseDto = new ResponseDto($this->payload['response']);
        $webhookDto  = new WebhookDto($this->payload['webhook']);

        try {
            $response = Http::send(
                $webhookDto->getMethod(),
                $webhookDto->getUri(),
                [
                    GuzzleRequestOptions::BODY    => $responseDto->toJson(),
                    GuzzleRequestOptions::TIMEOUT => self::REQUEST_TIMEOUT,
                ]
            );
        } catch (\Exception $e) {
            $this->fail($e);
            return;
        }
        $this->setOutput(
            (new ResponseDto())
                ->setStatusCode($response->status())
                ->setBody($response->body())
                ->setHeaders(Headers::process($response->headers()))
                ->toArray()
        );
    }
}
