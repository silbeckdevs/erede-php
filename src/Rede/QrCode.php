<?php

namespace Rede;

class QrCode
{
    use CreateTrait;

    private string $dateTimeExpiration;

    public function getDateTimeExpiration(): string
    {
        return $this->dateTimeExpiration;
    }

    public function setDateTimeExpiration(string $dateTimeExpiration): static
    {
        if (null !== $dateTimeExpiration && !preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}$/', $dateTimeExpiration)) {
            throw new \InvalidArgumentException('The due date must be in the format YYYY-MM-DDThh:mm:ss.');
        }

        $this->dateTimeExpiration = $dateTimeExpiration;

        return $this;
    }
}
