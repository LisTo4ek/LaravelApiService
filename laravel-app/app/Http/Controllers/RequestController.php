<?php


namespace App\Http\Controllers;
use App\Models\Status;
use App\Support\RequestDto;
use Illuminate\Http\Request;
use App\Jobs\ProcessRequestJob;

use App\Models\Response;

class RequestController extends Controller
{
    /**
     * @param string $uri
     * @param Request $request
     */
    public function process(string $uri, Request $request)
    {
        $requestDto = (new RequestDto())
            ->setMethod($request->getMethod())
            ->setUri($uri)
            ->setParams($request->header())
            ->setHeaders($request->all())
            ->setIps($request->getClientIps());

        $job = (new ProcessRequestJob($requestDto->toArray()))->onQueue('requests');

        dispatch($job);

        return response()->json(['jobId' => $job->getJobStatusId()]);
    }

    /**
     * @param int $jobStatusId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function status(int $jobStatusId)
    {
        if (!$status = Status::where('job_id', $jobStatusId)->first()) {
            return response('not found', 404);
        }

        $result = [
            'status' => $status->status
        ];

        if ($response = Response::where('job_status_id', $status->id)->first()) {
            $result['response'] = $response->response;
        }


        return response()->json($result);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function testOk()
    {
        $responseModel = new Response();
        $responseModel->job_status_id = 1;
        $responseModel->response = [
            'statusCode' => 222,
            'body' => [],
            'headers' => ['a' => 1],
        ];

        $responseModel->save();


       foreach (Response::all() as $flight) {
            dump( $flight->response);
        }

        return response('ok');
    }
}