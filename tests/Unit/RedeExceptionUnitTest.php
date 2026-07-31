<?php

namespace Rede\Tests\Unit;

use Rede\Exception\RedeException;
use Rede\Http\RedeResponse;
use Rede\Tests\BaseTestCase;
use Rede\Transaction;

class RedeExceptionUnitTest extends BaseTestCase
{
    public function testExceptionWithoutTransactionPreservesCurrentBehavior(): void
    {
        $exception = new RedeException('API error', 400);

        $this->assertSame('API error', $exception->getMessage());
        $this->assertSame(400, $exception->getCode());
        $this->assertNull($exception->getTransaction());
        $this->assertNull($exception->getHttpResponse());
    }

    public function testExceptionWithTransactionAttached(): void
    {
        $transaction = (new Transaction())
            ->jsonUnserialize('{"tid":"10462607311118416439","returnCode":"124","returnMessage":"Unauthorized."}');

        $exception = new RedeException('Unauthorized.', 124, null, $transaction);

        $this->assertSame($transaction, $exception->getTransaction());
        $this->assertNotNull($exception->getTransaction());
        $this->assertSame('10462607311118416439', $exception->getTransaction()->getTid());
        $this->assertSame('124', $exception->getTransaction()->getReturnCode());
    }

    public function testGetHttpResponseReturnsDirectResponse(): void
    {
        $httpResponse = new RedeResponse(400, '{"returnCode":"124"}');

        $exception = new RedeException('Unauthorized.', 124, null, null, $httpResponse);

        $this->assertSame($httpResponse, $exception->getHttpResponse());
    }

    public function testGetHttpResponseFallsBackToTransactionResponse(): void
    {
        $httpResponse = new RedeResponse(400, '{"returnCode":"124"}');
        $transaction = (new Transaction())->setHttpResponse($httpResponse);

        $exception = new RedeException('Unauthorized.', 124, null, $transaction);

        $this->assertSame($httpResponse, $exception->getHttpResponse());
    }
}
