<?php

namespace App\Jobs;

use App\Models\Response as ResponseModel;
use App\Support\Dto\RequestDto;
use App\Support\Dto\ResponseDto;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Imtigger\LaravelJobStatus\Trackable;
use GuzzleHttp\Psr7\Request as GuzzlePsr7Request;
use GuzzleHttp\Client as GuzzleHttpClient;

class ProcessRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

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
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        try {
            $requestDto = new RequestDto($this->request);
            $request    = new GuzzlePsr7Request($requestDto->getMethod(), $requestDto->getUri());

            // TODO: process headers
            // TODO: process params

            $client   = new GuzzleHttpClient();
            $response = $client->send($request);
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        } catch (\Exception $e) {
            $this->fail($e);
            return;
        }

        $responseModel                = new ResponseModel();
        $responseModel->job_status_id = $this->getJobStatusId();
        $responseModel->response      = (new ResponseDto())
            ->setStatusCode($response->getStatusCode())
            ->setBody($response->getBody()->__toString())
            ->setHeaders($response->getHeaders())
            ->toArray();

        $responseModel->save();
    }
}
