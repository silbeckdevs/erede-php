<?php

namespace Rede;

class Refund
{
    use CreateTrait;

    private ?int $amount = null;

    private ?\DateTime $refundDateTime = null;

    private ?string $refundId = null;

    private ?string $status = null;

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @return $this
     */
    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getRefundDateTime(): ?\DateTime
    {
        return $this->refundDateTime;
    }

    /**
     * @return $this
     *
     * @throws \Exception
     */
    public function setRefundDateTime(string $refundDateTime): static
    {
        $this->refundDateTime = new \DateTime($refundDateTime);

        return $this;
    }

    public function getRefundId(): ?string
    {
        return $this->refundId;
    }

    /**
     * @return $this
     */
    public function setRefundId(string $refundId): static
    {
        $this->refundId = $refundId;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return $this
     */
    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
