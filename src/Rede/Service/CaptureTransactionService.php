<?php

namespace Rede\Service;

use Rede\Exception\RedeException;
use Rede\Transaction;

class CaptureTransactionService extends AbstractTransactionsService
{
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

        return $this->sendRequest($json, AbstractService::PUT);
    }

    protected function getService(): string
    {
        if (null === $this->transaction) {
            throw new \RuntimeException('Transaction was not defined yet');
        }

        return sprintf('%s/%s', parent::getService(), $this->transaction->getTid());
    }
}
