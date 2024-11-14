<?php

namespace Rede;

trait SerializeTrait
{
    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return array_filter(get_object_vars($this), fn ($value): bool => null !== $value);
    }
}
