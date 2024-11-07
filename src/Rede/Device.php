<?php

namespace Rede;

class Device implements RedeSerializable
{
    use CreateTrait;
    use SerializeTrait;

    public function __construct(
        private string|int|null $ColorDepth = null,
        private ?string $DeviceType3ds = null,
        private ?bool $JavaEnabled = null,
        private string $Language = 'BR',
        private ?int $ScreenHeight = null,
        private ?int $ScreenWidth = null,
        private ?int $TimeZoneOffset = 3,
    ) {
    }

    public function getColorDepth(): ?string
    {
        return $this->ColorDepth;
    }

    /**
     * @return $this
     */
    public function setColorDepth(string $ColorDepth): static
    {
        $this->ColorDepth = $ColorDepth;

        return $this;
    }

    public function getDeviceType3ds(): ?string
    {
        return $this->DeviceType3ds;
    }

    /**
     * @return $this
     */
    public function setDeviceType3ds(string $DeviceType3ds): static
    {
        $this->DeviceType3ds = $DeviceType3ds;

        return $this;
    }

    public function getJavaEnabled(): ?bool
    {
        return $this->JavaEnabled;
    }

    /**
     * @return $this
     */
    public function setJavaEnabled(bool $JavaEnabled = true): static
    {
        $this->JavaEnabled = $JavaEnabled;

        return $this;
    }

    public function getLanguage(): string
    {
        return $this->Language;
    }

    /**
     * @return $this
     */
    public function setLanguage(string $Language): static
    {
        $this->Language = $Language;

        return $this;
    }

    public function getScreenHeight(): ?int
    {
        return $this->ScreenHeight;
    }

    /**
     * @return $this
     */
    public function setScreenHeight(int $ScreenHeight): static
    {
        $this->ScreenHeight = $ScreenHeight;

        return $this;
    }

    public function getScreenWidth(): ?int
    {
        return $this->ScreenWidth;
    }

    /**
     * @return $this
     */
    public function setScreenWidth(int $ScreenWidth): static
    {
        $this->ScreenWidth = $ScreenWidth;

        return $this;
    }

    public function getTimeZoneOffset(): ?int
    {
        return $this->TimeZoneOffset;
    }

    /**
     * @return $this
     */
    public function setTimeZoneOffset(int $TimeZoneOffset): static
    {
        $this->TimeZoneOffset = $TimeZoneOffset;

        return $this;
    }
}
