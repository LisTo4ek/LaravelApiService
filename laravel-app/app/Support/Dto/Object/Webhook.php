<?php

namespace App\Support\Dto\Object;

use App\Support\Dto\Object\Components\Http\Method;
use App\Support\Dto\Object\Components\Http\Uri;

/**
 * Class Webhook
 * @package App\Support\Dto\Object
 */
class Webhook extends SimpleObject
{
    use Method, Uri;

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return true;
    }
}
