<?php

namespace Rede;

class Iata implements RedeSerializable
{
    use SerializeTrait;

    private ?string $code = null;

    private ?string $departureTax = null;

    /**
     * @var array<Flight>
     */
    private array $flight = [];

    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return $this
     */
    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getDepartureTax(): ?string
    {
        return $this->departureTax;
    }

    /**
     * @return $this
     */
    public function setDepartureTax(string $departureTax): static
    {
        $this->departureTax = $departureTax;

        return $this;
    }

    /**
     * @return \ArrayIterator<int,Flight>
     */
    public function getFlightIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->flight);
    }

    /**
     * @return $this
     */
    public function setFlight(Flight $flight): static
    {
        $this->flight = [];
        $this->addFlight($flight);

        return $this;
    }

    /**
     * @return $this
     */
    public function addFlight(Flight $flight): static
    {
        $this->flight[] = $flight;

        return $this;
    }
}
