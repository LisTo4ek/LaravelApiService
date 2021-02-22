<?php

namespace App\Support\Dto\Forge;

use App\Support\Dto\Object\Components\Http\Method;
use App\Support\Dto\Object\Components\Http\Uri;
use App\Support\Dto\Object\Webhook as WebhookDto;

/**
 * Class Webhook
 * @package App\Support\Dto\Forge
 */
class Webhook
{
    use Method, Uri;

    /**
     * @param array $data
     * @return WebhookDto
     */
    public static function fromRawData(array $data): WebhookDto
    {
        $dto = new WebhookDto();

        if ($data['m']) {
            $dto->setMethod($data['m']);
        }

        if ($data['u']) {
            $dto->setUri($data['u']);
        }

        return $dto;
    }
}
