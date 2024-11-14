<?php

namespace Rede;

class QrCode implements RedeSerializable
{
    use SerializeTrait;
    use CreateTrait;

    private ?\DateTimeInterface $dateTimeExpiration = null;

    // Campos que retornam apenas na consulta
    private ?\DateTimeInterface $dateTime = null;

    private ?\DateTimeInterface $expirationQrCode = null;

    private ?string $qrCodeImage = null;

    private ?string $qrCodeData = null;

    private ?string $returnCode = null;

    private ?string $returnMessage = null;

    private string|int|null $affiliation = null;

    private ?string $kind = null;

    private ?string $reference = null;

    private string|int|null $amount = null;

    private ?string $tid = null;

    private ?string $status = null;

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'dateTimeExpiration' => $this->getDateTimeExpiration()?->format('c') ?: null,
        ];
    }

    public function getDateTimeExpiration(): ?\DateTimeInterface
    {
        return $this->dateTimeExpiration;
    }

    public function setDateTimeExpiration(\DateTimeInterface $dateTimeExpiration): static
    {
        $this->dateTimeExpiration = $dateTimeExpiration;

        return $this;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function getExpirationQrCode(): ?\DateTimeInterface
    {
        return $this->expirationQrCode;
    }

    public function getQrCodeImage(): ?string
    {
        return $this->qrCodeImage;
    }

    public function getQrCodeData(): ?string
    {
        return $this->qrCodeData;
    }

    public function getReturnCode(): ?string
    {
        return $this->returnCode;
    }

    public function getReturnMessage(): ?string
    {
        return $this->returnMessage;
    }

    public function getAffiliation(): int|string|null
    {
        return $this->affiliation;
    }

    public function getKind(): ?string
    {
        return $this->kind;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function getAmount(): int|string|null
    {
        return $this->amount;
    }

    public function getTid(): ?string
    {
        return $this->tid;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }
}
