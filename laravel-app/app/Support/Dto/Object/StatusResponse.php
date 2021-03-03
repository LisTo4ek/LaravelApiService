<?php

namespace App\Support\Dto\Object;

/**
 * Class Webhook
 * @package App\Support\Dto\Object
 */
class StatusResponse extends GenericObject
{
    /**
     * @var string
     */
    public string $status;

    /**
     * @var array
     */
    public array $response;

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status):self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @param array $response
     * @return $this
     */
    public function setResponse(array $response):self
    {
        $this->response = $response;
        return $this;
    }
}
