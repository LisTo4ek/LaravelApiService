<?php

namespace App\Support\Dto\Object\Components\Http;

trait Method
{
    /**
     * @var string
     */
    public string $method = '';

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }
}
