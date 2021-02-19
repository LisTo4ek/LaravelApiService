<?php


namespace App\Support;

class ResponseDto extends SimpleDataTransferObject
{
    public string $statusCode;
    public string $body;
    public array $headers;

    /**
     * @return string
     */
    public function getStatusCode(): string
    {
        return $this->statusCode;
    }

    /**
     * @param string $statusCode
     * @return ResponseDto
     */
    public function setStatusCode(string $statusCode): ResponseDto
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return ResponseDto
     */
    public function setBody(string $body): ResponseDto
    {
        $this->body = $body;
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
     * @return ResponseDto
     */
    public function setHeaders(array $headers): ResponseDto
    {
        $this->headers = $headers;
        return $this;
    }
}
