<?php

namespace Rede;

class Cart implements RedeSerializable
{
    use SerializeTrait;

    private ?Address $billing = null;

    private ?Consumer $consumer = null;

    private ?Iata $iata = null;

    /**
     * @var array<Item>
     */
    private array $items = [];

    /**
     * @var array<Address>
     */
    private array $shipping = [];

    public function address(int $type = Address::BOTH): Address
    {
        $address = new Address();

        if (($type & Address::BILLING) == Address::BILLING) {
            $this->setBillingAddress($address);
        }

        if (($type & Address::SHIPPING) == Address::SHIPPING) {
            $this->setShippingAddress($address);
        }

        return $address;
    }

    /**
     * @return $this
     */
    public function addItem(Item $item): static
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return $this
     */
    public function addShippingAddress(Address $shippingAddress): static
    {
        $this->shipping[] = $shippingAddress;

        return $this;
    }

    /**
     * @return $this
     */
    public function setShippingAddress(Address $shippingAddress): static
    {
        $this->shipping = [$shippingAddress];

        return $this;
    }

    /**
     * @return $this
     */
    public function setBillingAddress(Address $billingAddress): static
    {
        $this->billing = $billingAddress;

        return $this;
    }

    public function consumer(string $name, string $email, string $cpf): Consumer
    {
        $consumer = new Consumer($name, $email, $cpf);

        $this->setConsumer($consumer);

        return $consumer;
    }

    /**
     * @return $this
     */
    public function setFlight(Flight $flight): static
    {
        $this->iata = new Iata();
        $this->iata->setFlight($flight);

        return $this;
    }

    /**
     * @return $this
     */
    public function setIata(Iata $iata): static
    {
        $this->iata = $iata;

        return $this;
    }

    /**
     * @return Address[]
     */
    public function getShippingAddresses(): array
    {
        return $this->shipping;
    }

    public function getBilling(): ?Address
    {
        return $this->billing;
    }

    public function getConsumer(): ?Consumer
    {
        return $this->consumer;
    }

    public function setConsumer(Consumer $consumer): Cart
    {
        $this->consumer = $consumer;

        return $this;
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
