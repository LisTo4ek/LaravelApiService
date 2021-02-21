<?php


namespace App\Http\Controllers;

use App\Jobs\ProcessRequestJob;
use App\Models\Response as ResponseModel;
use App\Models\Status as StatusModel;
use App\Support\Dto\RequestDto;
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
        $requestDto = (new RequestDto())
            ->setMethod($request->getMethod())
            ->setUri($uri)
            ->setParams($request->header())
            ->setHeaders($request->all())
            ->setIps($request->getClientIps());

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
            return response('', 404)->json(['message' => 'Not found']);
        }

        $result = [
            'status' => $status->status
        ];

        if ($response = ResponseModel::where('job_status_id', $status->id)->first()) {
            $result['response'] = $response->response;
        }

        return response()->json($result);
    }
}
