<?php

namespace App\Support\Dto\Object;

use App\Support\Dto\Object\Components\Http\Body;
use App\Support\Dto\Object\Components\Http\Headers;
use App\Support\Dto\Object\Components\Http\StatusCode;

/**
 * Class Response
 * @package App\Support\Dto\Object
 */
class Response extends SimpleObject
{
    use StatusCode, Body, Headers;
}
