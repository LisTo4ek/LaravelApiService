<?php


namespace App\Support;

class RequestDto extends SimpleDataTransferObject
{
    public string $method;
    public string $uri;
    public array $headers;
    public array $params;
    public array $ips;


    public static function fromArray(array $params)
    {
        return new self([
            'method' => $params['method'],
            'uri' => $params['uri'],
            'headers' => $params['headers'],
            'params' => $params['params'],
            'ips' => $params['ips'],
        ]);
    }

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
