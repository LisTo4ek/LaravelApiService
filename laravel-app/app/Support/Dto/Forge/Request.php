<?php

namespace App\Support\Dto\Forge;

use App\Support\Json;
use Illuminate\Http\Request as HttpRequest;
use App\Support\Dto\Object\Request as RequestDto;
use App\Support\Dto\Forge\Webhook as WebhookForge;
use App\Support\Headers;

/**
 * Class Request
 * @package App\Support\Dto\Forge
 */
class Request
{
    /**
     * @param HttpRequest $request
     * @return RequestDto
     */
    public static function fromHttpRequest(HttpRequest $request): RequestDto
    {
        $requestDto = (new RequestDto())
            ->setMethod($request->getMethod())
            ->setQueryParams($request->query() ?: [])
            ->setIps($request->getClientIps())
            ->setBody($request->getContent());

        $params  = $request->post();
        $body    = $request->getContent();
        $headers = Headers::process($request->header());


        if (isset($headers['content-type']) && substr($headers['content-type'], 0, 20) === 'multipart/form-data;' && $params) {
            $requestDto->setFormParams($params);

            unset($headers['content-type']);

            if (isset($headers['content-length'])) {
                unset($headers['content-length']);
            }

            $body = '';
        }

        if (isset($headers['webhooks'])) {
            if (!empty($headers['webhooks'])) {
                $webhookDatas = Json::decode($headers['webhooks'], true);

                foreach ($webhookDatas as $webhookData) {
                    $requestDto->addWebHook(WebhookForge::fromRawData($webhookData)->toArray());
                }
            }

            unset($headers['webhooks']);
        }

        return $requestDto->setHeaders($headers)->setBody($body);
    }
}
