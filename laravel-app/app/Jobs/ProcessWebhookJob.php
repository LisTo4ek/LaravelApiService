<?php

namespace App\Jobs;

use App\Support\Dto\Object\Response as ResponseDto;
use App\Support\Dto\Object\Webhook as WebhookDto;
use App\Support\Headers as HeadersHelper;
use GuzzleHttp\RequestOptions as GuzzleRequestOptions;
use Illuminate\Bus\Queueable;
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

    public const SECTION_RESPONSE = 'response';
    public const SECTION_WEBHOOK  = 'webhook';

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
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function handle()
    {
        $responseDto = new ResponseDto($this->payload[self::SECTION_RESPONSE]);
        $webhookDto  = new WebhookDto($this->payload[self::SECTION_WEBHOOK]);

        $response = Http::send(
            $webhookDto->getMethod(),
            $webhookDto->getUri(),
            [
                GuzzleRequestOptions::BODY    => $responseDto->toJson(),
                GuzzleRequestOptions::TIMEOUT => self::REQUEST_TIMEOUT,
            ]
        );

        if ($response->serverError()) {
            $response->throw();
        }

        $this->setOutput(
            (new ResponseDto())
                ->setStatusCode($response->status())
                ->setBody($response->body())
                ->setHeaders(HeadersHelper::process($response->headers()))
                ->toArray()
        );
    }
}
