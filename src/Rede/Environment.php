<?php

namespace Rede;

class Environment implements RedeSerializable
{
    public const PRODUCTION = 'https://api.userede.com.br/erede';

    public const SANDBOX = 'https://api.userede.com.br/desenvolvedores';

    public const VERSION = 'v1';

    private ?string $ip = null;

    private ?string $sessionId = null;

    private string $endpoint;

    /**
     * Creates an environment with its base url and version.
     */
    private function __construct(string $baseUrl)
    {
        $this->endpoint = sprintf('%s/%s/', $baseUrl, Environment::VERSION);
    }

    /**
     * @return Environment A preconfigured production environment
     */
    public static function production(): Environment
    {
        return new Environment(Environment::PRODUCTION);
    }

    /**
     * @return Environment A preconfigured sandbox environment
     */
    public static function sandbox(): Environment
    {
        return new Environment(Environment::SANDBOX);
    }

    /**
     * @return string Gets the environment endpoint
     */
    public function getEndpoint(string $service): string
    {
        return $this->endpoint . $service;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @return $this
     */
    public function setIp(string $ip): static
    {
        $this->ip = $ip;

        return $this;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    /**
     * @return $this
     */
    public function setSessionId(string $sessionId): static
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function jsonSerialize(): mixed
    {
        $consumer = new \stdClass();
        $consumer->ip = $this->ip;
        $consumer->sessionId = $this->sessionId;

        return ['consumer' => $consumer];
    }
}
