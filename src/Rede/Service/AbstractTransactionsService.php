<?php

namespace Rede\Service;

use Psr\Log\LoggerInterface;
use Rede\Exception\RedeException;
use Rede\Store;
use Rede\Transaction;

abstract class AbstractTransactionsService extends AbstractService
{
    protected ?Transaction $transaction;

    private string $tid;

    /**
     * AbstractTransactionsService constructor.
     */
    public function __construct(Store $store, ?Transaction $transaction = null, ?LoggerInterface $logger = null)
    {
        parent::__construct($store, $logger);

        $this->transaction = $transaction;
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws RedeException
     */
    public function execute(): Transaction
    {
        $json = json_encode($this->transaction);

        if (!is_string($json)) {
            throw new \RuntimeException('Problem converting the Transaction object to json');
        }

        return $this->sendRequest($json, AbstractService::POST);
    }

    public function getTid(): string
    {
        return $this->tid;
    }

    /**
     * @return $this
     */
    public function setTid(string $tid): static
    {
        $this->tid = $tid;

        return $this;
    }

    /**
     * @see    AbstractService::getService()
     */
    protected function getService(): string
    {
        return 'transactions';
    }

    /**
     * @throws RedeException
     * @throws \InvalidArgumentException
     * @throws \Exception
     *
     * @see    AbstractService::parseResponse()
     */
    protected function parseResponse(string $response, int $statusCode): Transaction
    {
        $previous = null;

        if (null === $this->transaction) {
            $this->transaction = new Transaction();
        }

        try {
            $this->transaction->setHttpResponse(new \Rede\Http\RedeResponse($statusCode, $response));
            $this->transaction->jsonUnserialize($response);
        } catch (\InvalidArgumentException $e) {
            $previous = $e;
        }

        if ($statusCode >= 400) {
            throw new RedeException($this->transaction->getReturnMessage() ?? 'Error on getting the content from the API', (int) $this->transaction->getReturnCode(), $previous);
        }

        return $this->transaction;
    }
}
