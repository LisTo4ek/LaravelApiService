<?php

namespace App\Support\Dto\Object\Components\Http;

trait StatusCode
{
    /**
     * @var string
     */
    public string $statusCode;

    /**
     * @return string
     */
    public function getStatusCode(): string
    {
        return $this->statusCode;
    }

    /**
     * @param string $statusCode
     * @return $this
     */
    public function setStatusCode(string $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }
}
