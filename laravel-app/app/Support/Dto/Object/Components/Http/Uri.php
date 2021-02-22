<?php

namespace App\Support\Dto\Object\Components\Http;

trait Uri
{
    /**
     * @var string
     */
    public string $uri = '';

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return $this
     */
    public function setUri(string $uri): self
    {
        $this->uri = $uri;
        return $this;
    }
}
