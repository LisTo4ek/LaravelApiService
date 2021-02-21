<?php


namespace App\Support\Dto;

/**
 * Class RequestDto
 * @package App\Support\Dto
 */
class RequestDto extends SimpleDataTransferObject
{
    /**
     * @var string
     */
    public string $method;

    /**
     * @var string
     */
    public string $uri;

    /**
     * @var array
     */
    public array $headers;

    /**
     * @var array
     */
    public array $params;

    /**
     * @var array
     */
    public array $ips;

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return RequestDto
     */
    public function setMethod(string $method): RequestDto
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return RequestDto
     */
    public function setUri(string $uri): RequestDto
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     * @return RequestDto
     */
    public function setHeaders(array $headers): RequestDto
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return RequestDto
     */
    public function setParams(array $params): RequestDto
    {
        $this->params = $params;
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
     * @return RequestDto
     */
    public function setIps(array $ips): RequestDto
    {
        $this->ips = $ips;
        return $this;
    }
}
