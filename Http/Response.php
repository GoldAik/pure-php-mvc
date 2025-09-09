<?php

declare(strict_types = 1);

namespace App\Http;

class Response
{
    const DEFAULT_HEADER = 'Content-Type: text/html; charset=UTF-8';
    const JSON_HEADER = 'Content-Type: application/json; charset=UTF-8';

    protected $statusCode;
    protected $headers;
    protected $body;

    public function __construct(int $statusCode = 200, string $body = '', array $headers = [])
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
    }

    public static function create(int $statusCode = 200, string $body = '', array $headers = []): static
    {
        return new static(
            statusCode: $statusCode, 
            headers: $headers,
            body: $body
        );
    }

    public static function html(string $body, int $statusCode = 200): static
    {
        return new static(
            statusCode: $statusCode, 
            headers: ['Content-Type' => 'text/html'],
            body: $body
        );
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    private function generateResponse(): string
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        return $this->body;
    }

    public function __toString(): string
    {
        return $this->generateResponse();
    }
}