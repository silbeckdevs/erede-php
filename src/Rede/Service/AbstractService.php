<?php

namespace Rede\Service;

use Psr\Log\LoggerInterface;
use Rede\Exception\RedeException;
use Rede\Http\RedeHttpClient;
use Rede\Store;
use Rede\Transaction;

abstract class AbstractService extends RedeHttpClient
{
    public function __construct(Store $store, ?LoggerInterface $logger = null)
    {
        parent::__construct($store, $logger);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws RedeException
     */
    abstract public function execute(): Transaction;

    /**
     * @throws \RuntimeException
     */
    protected function sendRequest(string $body = '', string $method = 'GET'): Transaction
    {
        return $this->parseResponse($this->request($method, $this->store->getEnvironment()->getEndpoint($this->getService()), $body));
    }

    abstract protected function getService(): string;

    abstract protected function parseResponse(\Rede\Http\RedeResponse $httpResponse): Transaction;
}
