<?php

namespace Rede\Exception;

use Rede\Http\RedeResponse;
use Rede\Transaction;

class RedeException extends \RuntimeException
{
    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null,
        private ?Transaction $transaction = null,
        private ?RedeResponse $httpResponse = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function getHttpResponse(): ?RedeResponse
    {
        return $this->httpResponse ?? $this->transaction?->getHttpResponse() ?? null;
    }
}
