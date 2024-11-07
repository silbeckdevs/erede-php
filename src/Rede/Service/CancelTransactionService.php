<?php

namespace Rede\Service;

class CancelTransactionService extends AbstractTransactionsService
{
    protected function getService(): string
    {
        if (null === $this->transaction) {
            throw new \RuntimeException('Transaction was not defined yet');
        }

        return sprintf('%s/%s/refunds', parent::getService(), $this->transaction->getTid());
    }
}
