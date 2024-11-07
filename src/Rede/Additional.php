<?php

namespace Rede;

class Additional implements RedeSerializable
{
    use SerializeTrait;
    use CreateTrait;

    private ?int $gateway = null;

    private ?int $module = null;

    public function getGateway(): ?int
    {
        return $this->gateway;
    }

    /**
     * @return $this
     */
    public function setGateway(int $gateway): static
    {
        $this->gateway = $gateway;

        return $this;
    }

    public function getModule(): ?int
    {
        return $this->module;
    }

    /**
     * @return $this
     */
    public function setModule(int $module): static
    {
        $this->module = $module;

        return $this;
    }
}
