<?php

namespace Rede;

class Device implements RedeSerializable
{
    use CreateTrait;
    use SerializeTrait;

    public function __construct(
        private string|int|null $colorDepth = null,
        private ?string $deviceType3ds = null,
        private ?bool $javaEnabled = null,
        private string $language = 'BR',
        private ?int $screenHeight = null,
        private ?int $screenWidth = null,
        private ?int $timeZoneOffset = 3,
    ) {
    }

    public function getColorDepth(): string|int|null
    {
        return $this->colorDepth;
    }

    /**
     * @return $this
     */
    public function setColorDepth(string $colorDepth): static
    {
        $this->colorDepth = $colorDepth;

        return $this;
    }

    public function getDeviceType3ds(): ?string
    {
        return $this->deviceType3ds;
    }

    /**
     * @return $this
     */
    public function setDeviceType3ds(string $deviceType3ds): static
    {
        $this->deviceType3ds = $deviceType3ds;

        return $this;
    }

    public function getJavaEnabled(): ?bool
    {
        return $this->javaEnabled;
    }

    /**
     * @return $this
     */
    public function setJavaEnabled(bool $JavaEnabled = true): static
    {
        $this->javaEnabled = $JavaEnabled;

        return $this;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @return $this
     */
    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getScreenHeight(): ?int
    {
        return $this->screenHeight;
    }

    /**
     * @return $this
     */
    public function setScreenHeight(int $screenHeight): static
    {
        $this->screenHeight = $screenHeight;

        return $this;
    }

    public function getScreenWidth(): ?int
    {
        return $this->screenWidth;
    }

    /**
     * @return $this
     */
    public function setScreenWidth(int $screenWidth): static
    {
        $this->screenWidth = $screenWidth;

        return $this;
    }

    public function getTimeZoneOffset(): ?int
    {
        return $this->timeZoneOffset;
    }

    /**
     * @return $this
     */
    public function setTimeZoneOffset(int $timeZoneOffset): static
    {
        $this->timeZoneOffset = $timeZoneOffset;

        return $this;
    }
}
