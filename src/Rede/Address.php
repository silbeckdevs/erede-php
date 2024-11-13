<?php

namespace Rede;

class Address implements RedeSerializable
{
    use SerializeTrait;

    public const BILLING = 1;

    public const SHIPPING = 2;

    public const BOTH = 3;

    public const APARTMENT = 1;

    public const HOUSE = 2;

    public const COMMERCIAL = 3;

    public const OTHER = 4;

    private ?string $address = null;

    private ?string $addresseeName = null;

    private ?string $city = null;

    private ?string $complement = null;

    private ?string $neighbourhood = null;

    private ?string $number = null;

    private ?string $state = null;

    private ?int $type = null;

    private ?string $zipCode = null;

    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @return $this
     */
    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getAddresseeName(): ?string
    {
        return $this->addresseeName;
    }

    /**
     * @return $this
     */
    public function setAddresseeName(?string $addresseeName): static
    {
        $this->addresseeName = $addresseeName;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return $this
     */
    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    /**
     * @return $this
     */
    public function setComplement(?string $complement): static
    {
        $this->complement = $complement;

        return $this;
    }

    public function getNeighbourhood(): ?string
    {
        return $this->neighbourhood;
    }

    /**
     * @return $this
     */
    public function setNeighbourhood(?string $neighbourhood): static
    {
        $this->neighbourhood = $neighbourhood;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * @return $this
     */
    public function setNumber(?string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @return $this
     */
    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @return $this
     */
    public function setType(?int $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    /**
     * @return $this
     */
    public function setZipCode(?string $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }
}
