<?php

class QrCodeResponse
{
    private string $reference;
    private string $tid;
    private string $dateTime;
    private int $amount;
    private string $qrCodeResponse;
    private string $returnCode;
    private string $returnMessage;

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): void
    {
        $this->reference = $reference;
    }

    public function getTid(): string
    {
        return $this->tid;
    }

    public function setTid(string $tid): void
    {
        $this->tid = $tid;
    }

    public function getDateTime(): string
    {
        return $this->dateTime;
    }

    public function setDateTime(string $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getQrCodeResponse(): string
    {
        return $this->qrCodeResponse;
    }

    public function setQrCodeResponse(string $qrCodeResponse): void
    {
        $this->qrCodeResponse = $qrCodeResponse;
    }

    public function getReturnCode(): string
    {
        return $this->returnCode;
    }

    public function setReturnCode(string $returnCode): void
    {
        $this->returnCode = $returnCode;
    }

    public function getReturnMessage(): string
    {
        return $this->returnMessage;
    }

    public function setReturnMessage(string $returnMessage): void
    {
        $this->returnMessage = $returnMessage;
    }
}
