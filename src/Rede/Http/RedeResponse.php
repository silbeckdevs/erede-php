<?php

namespace Rede\Http;

class RedeResponse
{
    public function __construct(private int $statusCode, private string $body)
    {
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
