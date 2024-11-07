<?php

namespace Rede\Service;

use Rede\Exception\RedeException;
use Rede\Transaction;

class GetTransactionService extends AbstractTransactionsService
{
    private ?string $reference = null;

    private bool $refund = false;

    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws RedeException
     */
    public function execute(): Transaction
    {
        return $this->sendRequest();
    }

    /**
     * @return $this
     */
    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return $this
     */
    public function setRefund(bool $refund = true): static
    {
        $this->refund = $refund;

        return $this;
    }

    protected function getService(): string
    {
        if (null !== $this->reference) {
            return sprintf('%s?reference=%s', parent::getService(), $this->reference);
        }

        if ($this->refund) {
            return sprintf('%s/%s/refunds', parent::getService(), $this->getTid());
        }

        return sprintf('%s/%s', parent::getService(), $this->getTid());
    }
}
