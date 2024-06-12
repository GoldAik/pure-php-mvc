<?php

declare(strict_types = 1);

namespace App\Http;

use App\App;

class Response{
    const DEFAULT_HEADER = 'Content-Type: text/html; charset=UTF-8';
    const JSON_HEADER = 'Content-Type: application/json; charset=UTF-8';

    public function __construct(private int $statusCode = 200, private string $header = self::DEFAULT_HEADER, private string $body = '') {

    }

    public static function do(int $statusCode, string $header, string $body): static
    {
        return new static($statusCode, $header, $body);
    }

    protected function getStatusCode(): int
    {
        return $this->statusCode;
    }

    protected function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }

    protected function getHeader(): string
    {
        return $this->header;
    }

    protected function setHeader(string $header)
    {
        $this->header = $header;
    }

    protected function getBody(): string 
    {
        return $this->body;
    }

    protected function setBody(string $body)
    {
        $this->body = $body;
    }

    protected function send()
    {
        http_response_code($this->statusCode);
        header($this->header);
    }

    public function __toString(): string
    {
        $this->send();
        return $this->body;
    }
}