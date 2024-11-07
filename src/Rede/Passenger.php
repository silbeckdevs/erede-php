<?php

namespace Rede;

class Passenger implements RedeSerializable
{
    use SerializeTrait;

    private ?Phone $phone = null;

    public function __construct(private string $name, private string $email, private string $ticket)
    {
    }

    public function getPhone(): ?Phone
    {
        return $this->phone;
    }

    /**
     * @return $this
     */
    public function setPhone(Phone $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return $this
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTicket(): string
    {
        return $this->ticket;
    }

    /**
     * @return $this
     */
    public function setTicket(string $ticket): static
    {
        $this->ticket = $ticket;

        return $this;
    }
}
