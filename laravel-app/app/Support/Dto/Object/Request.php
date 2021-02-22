<?php

namespace App\Support\Dto\Object;

use App\Support\Dto\Object\Components\Http\Body;
use App\Support\Dto\Object\Components\Http\Headers;
use App\Support\Dto\Object\Components\Http\Method;
use App\Support\Dto\Object\Components\Http\Uri;

/**
 * Class Request
 * @package App\Support\Dto\Object
 */
class Request extends SimpleObject
{
    use Method, Uri, Body, Headers;

    /**
     * @var array
     */
    public array $queryParams = [];

    /**
     * @var array
     */
    public array $formParams = [];

    /**
     * @var array
     */
    public array $ips = [];

    /**
     * @var array
     */
    public array $webHooks = [];

    /**
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * @param array $queryParams
     * @return $this
     */
    public function setQueryParams(array $queryParams): self
    {
        $this->queryParams = $queryParams;
        return $this;
    }

    /**
     * @return array
     */
    public function getFormParams(): array
    {
        return $this->formParams;
    }

    /**
     * @param array $formParams
     * @return $this
     */
    public function setFormParams(array $formParams): self
    {
        $this->formParams = $formParams;
        return $this;
    }

    /**
     * @return array
     */
    public function getIps(): array
    {
        return $this->ips;
    }

    /**
     * @param array $ips
     * @return $this
     */
    public function setIps(array $ips): self
    {
        $this->ips = $ips;
        return $this;
    }

    /**
     * @return array
     */
    public function getWebHooks(): array
    {
        return $this->webHooks;
    }

    /**
     * @param array $webHooks
     * @return $this
     */
    public function setWebHooks(array $webHooks): self
    {
        $this->webHooks = $webHooks;
        return $this;
    }

    /**
     * @param array $webHook
     * @return $this
     */
    public function addWebHook(array $webHook): self
    {
        $this->webHooks[] = $webHook;
        return $this;
    }
}
