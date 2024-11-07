<?php

namespace Rede;

class Brand
{
    use CreateTrait;

    private ?string $name = null;

    private ?string $returnCode = null;
    private ?string $returnMessage = null;

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
}
