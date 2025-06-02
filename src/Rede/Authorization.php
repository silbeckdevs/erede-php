<?php

namespace Rede;

class Authorization
{
    use CreateTrait;

    private ?string $affiliation = null;

    private ?int $amount = null;

    private ?string $authorizationCode = null;

    private ?string $cardBin = null;

    private ?string $cardHolderName = null;

    private ?\DateTime $dateTime = null;

    private ?int $installments = null;

    private ?string $kind = null;

    private ?string $last4 = null;

    private ?string $nsu = null;

    private ?string $origin = null;

    private ?string $reference = null;

    private ?string $returnCode = null;

    private ?string $returnMessage = null;

    private ?string $status = null;

    private ?string $subscription = null;

    private ?string $tid = null;

    private ?Brand $brand = null;

    /**
     * @return array<string, class-string>
     */
    protected function getObjectMapping(): array
    {
        return ['brand' => Brand::class];
    }

    // gets and sets
    public function getAffiliation(): ?string
    {
        return $this->affiliation;
    }

    /**
     * @return $this
     */
    public function setAffiliation(?string $affiliation): static
    {
        $this->affiliation = $affiliation;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @return $this
     */
    public function setAmount(?int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAuthorizationCode(): ?string
    {
        return $this->authorizationCode;
    }

    /**
     * @return $this
     */
    public function setAuthorizationCode(?string $authorizationCode): static
    {
        $this->authorizationCode = $authorizationCode;

        return $this;
    }

    public function getCardBin(): ?string
    {
        return $this->cardBin;
    }

    /**
     * @return $this
     */
    public function setCardBin(?string $cardBin): static
    {
        $this->cardBin = $cardBin;

        return $this;
    }

    public function getCardHolderName(): ?string
    {
        return $this->cardHolderName;
    }

    /**
     * @return $this
     */
    public function setCardHolderName(?string $cardHolderName): static
    {
        $this->cardHolderName = $cardHolderName;

        return $this;
    }

    public function getDateTime(): ?\DateTime
    {
        return $this->dateTime;
    }

    /**
     * @return $this
     */
    public function setDateTime(?\DateTime $dateTime): static
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getInstallments(): ?int
    {
        return $this->installments;
    }

    /**
     * @return $this
     */
    public function setInstallments(?int $installments): static
    {
        $this->installments = $installments;

        return $this;
    }

    public function getKind(): ?string
    {
        return $this->kind;
    }

    /**
     * @return $this
     */
    public function setKind(?string $kind): static
    {
        $this->kind = $kind;

        return $this;
    }

    public function getLast4(): ?string
    {
        return $this->last4;
    }

    /**
     * @return $this
     */
    public function setLast4(?string $last4): static
    {
        $this->last4 = $last4;

        return $this;
    }

    public function getNsu(): ?string
    {
        return $this->nsu;
    }

    /**
     * @return $this
     */
    public function setNsu(?string $nsu): static
    {
        $this->nsu = $nsu;

        return $this;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    /**
     * @return $this
     */
    public function setOrigin(?string $origin): static
    {
        $this->origin = $origin;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @return $this
     */
    public function setReference(?string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getReturnCode(): ?string
    {
        return $this->returnCode;
    }

    /**
     * @return $this
     */
    public function setReturnCode(?string $returnCode): static
    {
        $this->returnCode = $returnCode;

        return $this;
    }

    public function getReturnMessage(): ?string
    {
        return $this->returnMessage;
    }

    /**
     * @return $this
     */
    public function setReturnMessage(?string $returnMessage): static
    {
        $this->returnMessage = $returnMessage;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return $this
     */
    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getSubscription(): ?string
    {
        return $this->subscription;
    }

    /**
     * @return $this
     */
    public function setSubscription(?string $subscription): static
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function getTid(): ?string
    {
        return $this->tid;
    }

    /**
     * @return $this
     */
    public function setTid(?string $tid): static
    {
        $this->tid = $tid;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    /**
     * @return $this
     */
    public function setBrand(Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }
}
