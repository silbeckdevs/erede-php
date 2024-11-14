<?php

namespace Rede;

class Phone implements RedeSerializable
{
    use SerializeTrait;

    public const CELLPHONE = 1;

    public const HOME = 2;

    public const WORK = 3;

    public const OTHER = 4;

    /**
     * Phone constructor.
     */
    public function __construct(private string $ddd, private string $number, private int $type = Phone::CELLPHONE)
    {
    }

    public function getDdd(): string
    {
        return $this->ddd;
    }

    /**
     * @return $this
     */
    public function setDdd(string $ddd): static
    {
        $this->ddd = $ddd;

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

    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return $this
     */
    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }
}
