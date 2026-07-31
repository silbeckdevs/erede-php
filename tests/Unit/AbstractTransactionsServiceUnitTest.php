<?php

namespace Rede\Tests\Unit;

use Rede\Environment;
use Rede\Exception\RedeException;
use Rede\Http\RedeResponse;
use Rede\Service\CreateTransactionService;
use Rede\Store;
use Rede\Tests\BaseTestCase;
use Rede\Transaction;

class AbstractTransactionsServiceUnitTest extends BaseTestCase
{
    private Store $store;

    protected function setUp(): void
    {
        parent::setUp();

        $this->store = new Store('filiation', 'token', Environment::sandbox());
    }

    public function testParseResponseOnHttp400PreservesPartialTransactionData(): void
    {
        $json = json_encode([
            'tid' => '10462607311118416439',
            'nsu' => '306718396',
            'returnCode' => '124',
            'returnMessage' => 'Unauthorized. Invalid security code.',
            'brand' => [
                'name' => 'Mastercard',
                'returnCode' => '124',
                'returnMessage' => 'Unauthorized. Invalid security code.',
            ],
        ], JSON_THROW_ON_ERROR);

        $service = new CreateTransactionService($this->store, new Transaction());

        try {
            $this->invokeParseResponse($service, new RedeResponse(400, $json));
            $this->fail('Expected RedeException to be thrown');
        } catch (RedeException $exception) {
            $transaction = $exception->getTransaction();

            $this->assertNotNull($transaction);
            $this->assertSame('10462607311118416439', $transaction->getTid());
            $this->assertSame('124', $transaction->getReturnCode());
            $this->assertSame('Unauthorized. Invalid security code. | Brand: name=Mastercard, returnCode=124, returnMessage=Unauthorized. Invalid security code.', $exception->getMessage());
            $this->assertSame(124, $exception->getCode());
            $this->assertSame('124', $transaction->getBrand()?->getReturnCode());
            $this->assertSame($json, $exception->getHttpResponse()?->getBody());
        }
    }

    public function testParseResponseOnHttp400WithInvalidJsonStillExposesHttpResponse(): void
    {
        $service = new CreateTransactionService($this->store, new Transaction());
        $httpResponse = new RedeResponse(400, 'not valid json');

        try {
            $this->invokeParseResponse($service, $httpResponse);
            $this->fail('Expected RedeException to be thrown');
        } catch (RedeException $exception) {
            $this->assertSame('Error on getting the content from the API', $exception->getMessage());
            $this->assertSame(0, $exception->getCode());
            $this->assertSame($httpResponse, $exception->getHttpResponse());
        }
    }

    public function testParseResponseOnHttp400AppendsBrandDetailsToErrorMessage(): void
    {
        $json = json_encode([
            'tid' => '10462607311118416439',
            'returnCode' => '57',
            'returnMessage' => 'Generic error',
            'brand' => [
                'name' => 'Mastercard',
                'returnCode' => '124',
                'returnMessage' => 'Unauthorized. Invalid security code.',
            ],
        ], JSON_THROW_ON_ERROR);

        $service = new CreateTransactionService($this->store, new Transaction());

        try {
            $this->invokeParseResponse($service, new RedeResponse(400, $json));
            $this->fail('Expected RedeException to be thrown');
        } catch (RedeException $exception) {
            $this->assertSame('Generic error | Brand: name=Mastercard, returnCode=124, returnMessage=Unauthorized. Invalid security code.', $exception->getMessage());
            $this->assertSame(57, $exception->getCode());
        }
    }

    public function testParseResponseOnHttp400WithInvalidJsonUsesReturnMessageFromBody(): void
    {
        $json = json_encode([
            'returnCode' => '999',
            'returnMessage' => 'Declined by issuer.',
        ], JSON_THROW_ON_ERROR);

        $service = new CreateTransactionService($this->store, new Transaction());

        try {
            $this->invokeParseResponse($service, new RedeResponse(400, $json));
            $this->fail('Expected RedeException to be thrown');
        } catch (RedeException $exception) {
            $this->assertSame('Declined by issuer.', $exception->getMessage());
            $this->assertSame(999, $exception->getCode());
            $this->assertSame($json, $exception->getHttpResponse()?->getBody());
        }
    }

    private function invokeParseResponse(CreateTransactionService $service, RedeResponse $httpResponse): Transaction
    {
        $reflection = new \ReflectionMethod(CreateTransactionService::class, 'parseResponse');
        $reflection->setAccessible(true);

        return $reflection->invoke($service, $httpResponse);
    }
}
