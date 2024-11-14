<?php

namespace Rede;

class Capture
{
    use CreateTrait;

    private ?int $amount = null;

    private ?\DateTime $dateTime = null;

    private ?string $nsu = null;

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): Capture
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDateTime(): ?\DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(?\DateTime $dateTime): Capture
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getNsu(): ?string
    {
        return $this->nsu;
    }

    public function setNsu(?string $nsu): Capture
    {
        $this->nsu = $nsu;

        return $this;
    }
}
