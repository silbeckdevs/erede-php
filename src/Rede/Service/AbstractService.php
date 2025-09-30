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
        // TODO Obtém o access token via OAuth 2.0
        // $accessToken = $this->store->getOAuth()->getAccessToken();
        // $headers[] = 'Authorization: Bearer ' . $accessToken;

        [$response, $statusCode] = $this->request($method, $this->store->getEnvironment()->getEndpoint($this->getService()), $body);

        return $this->parseResponse($response, $statusCode);
    }

    /**
     * @return string Gets the service that will be used on the request
     */
    abstract protected function getService(): string;

    /**
     * @param string $response   Parses the HTTP response from Rede
     * @param int    $statusCode The HTTP status code
     */
    abstract protected function parseResponse(string $response, int $statusCode): Transaction;
}
