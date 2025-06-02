<?php

namespace Rede;

class Brand
{
    use CreateTrait;

    private ?string $name = null;

    private ?string $returnCode = null;

    private ?string $returnMessage = null;

    private ?string $merchantAdviceCode = null;

    private ?string $authorizationCode = null;

    private ?string $brandTid = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Brand
    {
        $this->name = $name;

        return $this;
    }

    public function getReturnCode(): ?string
    {
        return $this->returnCode;
    }

    public function setReturnCode(?string $returnCode): Brand
    {
        $this->returnCode = $returnCode;

        return $this;
    }

    public function getReturnMessage(): ?string
    {
        return $this->returnMessage;
    }

    public function setReturnMessage(?string $returnMessage): Brand
    {
        $this->returnMessage = $returnMessage;

        return $this;
    }

    public function getMerchantAdviceCode(): ?string
    {
        return $this->merchantAdviceCode;
    }

    public function setMerchantAdviceCode(?string $merchantAdviceCode): Brand
    {
        $this->merchantAdviceCode = $merchantAdviceCode;

        return $this;
    }

    public function getAuthorizationCode(): ?string
    {
        return $this->authorizationCode;
    }

    public function setAuthorizationCode(?string $authorizationCode): Brand
    {
        $this->authorizationCode = $authorizationCode;

        return $this;
    }

    public function getBrandTid(): ?string
    {
        return $this->brandTid;
    }

    public function setBrandTid(?string $brandTid): Brand
    {
        $this->brandTid = $brandTid;

        return $this;
    }
}
