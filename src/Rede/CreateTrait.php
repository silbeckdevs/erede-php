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
        $objectMapping = method_exists($this, 'getObjectMapping') ? $this->getObjectMapping() : [];

        foreach ($bodyKeys as $property => $value) {
            if (property_exists($this, $property) && null !== $value) {
                if (in_array($property, $dateTimeProps) && is_string($value)) {
                    $value = new \DateTime($value);
                } elseif ($objectMapping && isset($objectMapping[$property]) && !empty($value)) {
                    // @phpstan-ignore-next-line
                    $value = (new $objectMapping[$property]())?->populate($value);
                }

                $this->{$property} = $value;
            }
        }

        return $this;
    }
}
