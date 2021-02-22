<?php

namespace App\Support\Dto\Object\Components\Http;

trait Body
{
    /**
     * @var string
     */
    public string $body = '';

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }
}
