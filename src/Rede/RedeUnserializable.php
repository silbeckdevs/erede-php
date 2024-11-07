<?php

namespace Rede;

interface RedeUnserializable
{
    /**
     * @return $this
     */
    public function jsonUnserialize(string $serialized): static;
}
