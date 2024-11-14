<?php

namespace Rede;

class SubMerchant
{
    /**
     * SubMerchant constructor.
     */
    public function __construct(private string $mcc, private string $city, private string $country)
    {
    }

    public function getMcc(): string
    {
        return $this->mcc;
    }

    /**
     * @return $this
     */
    public function setMcc(string $mcc): static
    {
        $this->mcc = $mcc;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return $this
     */
    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @return $this
     */
    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }
}
