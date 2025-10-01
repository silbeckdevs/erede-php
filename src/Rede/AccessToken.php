<?php

namespace Rede;

class AccessToken
{
    use SerializeTrait;
    use CreateTrait;

    public function __construct(
        private ?string $access_token = null,
        private ?int $expires_in = null,
        private ?int $expires_at = null,
        private ?string $token_type = null,
        private ?string $scope = null,
    ) {
    }

    public function isValid(): bool
    {
        if (empty($this->access_token)) {
            return false;
        }

        if (null !== $this->expires_at && time() >= $this->expires_at) {
            return false;
        }

        return true;
    }

    // gets and sets
    public function getTokenType(): ?string
    {
        return $this->token_type;
    }

    public function setTokenType(?string $token_type): static
    {
        $this->token_type = $token_type;

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->access_token;
    }

    public function setAccessToken(?string $access_token): static
    {
        $this->access_token = $access_token;

        return $this;
    }

    public function getExpiresIn(): ?int
    {
        return $this->expires_in;
    }

    public function setExpiresIn(?int $expires_in): static
    {
        $this->expires_in = $expires_in;

        return $this;
    }

    public function getScope(): ?string
    {
        return $this->scope;
    }

    public function setScope(?string $scope): static
    {
        $this->scope = $scope;

        return $this;
    }

    public function getExpiresAt(): ?int
    {
        return $this->expires_at;
    }

    public function setExpiresAt(?int $expires_at): static
    {
        $this->expires_at = $expires_at;

        return $this;
    }
}
