<?php

namespace Rede;

class BillingAddress implements RedeSerializable
{
    use CreateTrait;
    use SerializeTrait;

    public function __construct(
        private ?string $address = null,
        private ?string $city = null,
        private string|int|null $postalcode = null,
        private ?string $state = null,
        private ?string $country = null,
        private ?string $emailAddress = null,
        private ?string $phoneNumber = null,
    ) {
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalcode(): string|int|null
    {
        return $this->postalcode;
    }

    public function setPostalcode(string|int|null $postalcode): static
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(?string $emailAddress): static
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }
}
