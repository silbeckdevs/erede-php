<?php

namespace Rede;

use Rede\Http\RedeResponse;

trait ResponseTrait
{
    private ?RedeResponse $httpResponse = null;

    public function getHttpResponse(): ?RedeResponse
    {
        return $this->httpResponse;
    }

    public function setHttpResponse(?RedeResponse $httpResponse): static
    {
        $this->httpResponse = $httpResponse;

        return $this;
    }
}
