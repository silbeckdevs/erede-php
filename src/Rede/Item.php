<?php

namespace Rede;

class Item implements RedeSerializable
{
    use SerializeTrait;

    public const PHYSICAL = 1;

    public const DIGITAL = 2;

    public const SERVICE = 3;

    public const AIRLINE = 4;

    private ?int $amount = null;

    private ?string $description = null;

    private ?int $discount = null;

    private ?int $freight = null;

    private ?string $shippingType = null;

    /**
     * Item constructor.
     */
    public function __construct(private string $id, private int $quantity, private int $type = Item::PHYSICAL)
    {
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @return $this
     */
    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return $this
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    /**
     * @return $this
     */
    public function setDiscount(int $discount): static
    {
        $this->discount = $discount;

        return $this;
    }

    public function getFreight(): ?int
    {
        return $this->freight;
    }

    /**
     * @return $this
     */
    public function setFreight(int $freight): static
    {
        $this->freight = $freight;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return $this
     */
    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @return $this
     */
    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getShippingType(): ?string
    {
        return $this->shippingType;
    }

    /**
     * @return $this
     */
    public function setShippingType(string $shippingType): static
    {
        $this->shippingType = $shippingType;

        return $this;
    }

    public function getType(): ?int
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
