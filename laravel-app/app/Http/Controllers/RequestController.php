<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessRequestJob;
use App\Models\Response as ResponseModel;
use App\Models\Status as StatusModel;
use App\Support\Dto\Forge\Request as RequestDtoForge;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /**
     * Save request as a job
     *
     * @param string $uri
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function process(string $uri, Request $request)
    {
        $requestDto = RequestDtoForge::fromHttpRequest($request)->setUri($uri);

        $job = (new ProcessRequestJob($requestDto->toArray()))->onQueue('requests');

        dispatch($job);

        return response()->json(['jobStatusId' => $job->getJobStatusId()]);
    }

    /**
     * Get job status
     *
     * @param int $jobStatusId
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(int $jobStatusId)
    {
        if (!$status = StatusModel::where('id', $jobStatusId)->first()) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $result = [
            'status' => $status->status
        ];

        if ($response = ResponseModel::where('job_status_id', $status->id)->first()) {
            $result['response'] = $response->response;
        }

        return response()->json($result);
    }

    /**
     * @param int $status
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @todo This method created for demo purpose only
     */
    public function test(int $status, Request $request)
    {
        $r = [
            'method'     => $request->getMethod(),
            'uri'        => $request->getUri(),
            'parameters' => $request->all(),
            'headers'    => $request->header(),
            'body'       => $request->getContent(),
        ];

        return response()->json($r, $status);
    }
}
