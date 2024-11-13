<?php

namespace Rede;

trait CreateTrait
{
    /**
     * @throws \Exception
     */
    public function populate(object $body): static
    {
        $bodyKeys = get_object_vars($body);
        $dateTimeProps = ['requestDateTime', 'dateTime', 'refundDateTime', 'dateTimeExpiration', 'expirationQrCode'];

        foreach ($bodyKeys as $property => $value) {
            if (property_exists($this, $property) && null !== $value) {
                if (in_array($property, $dateTimeProps) && is_string($value)) {
                    $value = new \DateTime($value);
                }

                $this->{$property} = $value;
            }
        }

        return $this;
    }
}
