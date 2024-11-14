<?php

namespace Rede;

class Flight implements RedeSerializable
{
    use SerializeTrait;

    /**
     * @var array<Passenger>
     */
    private array $passenger = [];

    public function __construct(private string $number, private string $from, private string $to, private string $date)
    {
    }

    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return $this
     */
    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @return $this
     */
    public function setFrom(string $from): static
    {
        $this->from = $from;

        return $this;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return $this
     */
    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return array<Passenger>
     */
    public function getPassenger(): array
    {
        return $this->passenger;
    }

    /**
     * @return $this
     */
    public function setPassenger(Passenger $passenger): static
    {
        $this->passenger = [];
        $this->addPassenger($passenger);

        return $this;
    }

    /**
     * @return $this
     */
    public function addPassenger(Passenger $passenger): static
    {
        $this->passenger[] = $passenger;

        return $this;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @return $this
     */
    public function setTo(string $to): static
    {
        $this->to = $to;

        return $this;
    }
}
