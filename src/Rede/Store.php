<?php

namespace Rede;

class Store
{
    private Environment $environment;

    private ?AccessToken $accessToken = null;

    /**
     * Creates a store.
     *
     * @param string           $filiation   ClientId para autenticação OAuth 2.0
     * @param string           $token       ClientSecret para autenticação OAuth 2.0
     * @param Environment|null $environment if none provided, production will be used
     */
    public function __construct(
        private string $filiation,
        private string $token,
        ?Environment $environment = null,
    ) {
        $this->environment = $environment ?? Environment::production();
    }

    public function getEnvironment(): Environment
    {
        return $this->environment;
    }

    /**
     * @return $this
     */
    public function setEnvironment(Environment $environment): static
    {
        $this->environment = $environment;

        return $this;
    }

    public function getFiliation(): string
    {
        return $this->filiation;
    }

    /**
     * @return $this
     */
    public function setFiliation(string $filiation): static
    {
        $this->filiation = $filiation;

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return $this
     */
    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getAccessToken(): ?AccessToken
    {
        return $this->accessToken;
    }

    public function setAccessToken(?AccessToken $accessToken): static
    {
        $this->accessToken = $accessToken;

        return $this;
    }
}
