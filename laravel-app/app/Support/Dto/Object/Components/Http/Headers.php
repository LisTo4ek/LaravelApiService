<?php

namespace App\Support\Dto\Object\Components\Http;

trait Headers
{
    /**
     * @var array
     */
    public array $headers = [];

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }
}
