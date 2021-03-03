<?php

namespace App\Http\Controllers;

use App\Jobs\JobQueue;
use App\Jobs\ProcessRequestJob;
use App\Models\Status as StatusModel;
use App\Support\Dto\Forge\Request as RequestDtoForge;
use App\Support\Dto\Object\StatusResponse;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /**
     * Save request as a job
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function process(Request $request)
    {
        $requestDto = RequestDtoForge::fromHttpRequest($request);

        if (!$requestDto->isValid()) {
            return response()->json(['message' => 'Invalid Request'], 400);
        }

        $job = (new ProcessRequestJob($requestDto->toArray()))->onQueue(JobQueue::REQUESTS);

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
            return response()->json(['message' => 'Not Found'], 404);
        }

        $resultDto = (new StatusResponse())->setStatus($status->status);

        if ($status->output) {
            $resultDto->setResponse($status->output);
        }

        return response()->json($resultDto->toArray());
    }

    /**
     * @param int     $status
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
