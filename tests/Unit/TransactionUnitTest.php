<?php

namespace Rede\Tests\Unit;

use Rede\Tests\BaseTestCase;
use Rede\Transaction;

class TransactionUnitTest extends BaseTestCase
{
    public function testShouldPopulateTransaction(): void
    {
        $transaction = (new Transaction())->jsonUnserialize($this->getJsonTransactionMock());

        $this->assertNull($transaction->getAuthorization());
        $this->assertNull($transaction->getCapture());

        $this->assertSame(20099, $transaction->getAmount());
        $this->assertSame('12345678', $transaction->getTid());
        $this->assertSame('306718396', $transaction->getNsu());
        $this->assertSame('544828', $transaction->getCardBin());
        $this->assertSame('0007', $transaction->getLast4());
        $this->assertSame('00', $transaction->getReturnCode());
        $this->assertSame('2025-06-02T13:15:39-03:00', $transaction->getDateTime()?->format('c'));

        $this->assertIsObject($transaction->getBrand());
        $this->assertSame('Mastercard', $transaction->getBrand()->getName());
        $this->assertSame('00', $transaction->getBrand()->getReturnCode());
        $this->assertSame('Success.', $transaction->getBrand()->getReturnMessage());
        $this->assertSame('MCS1616339888484', $transaction->getBrand()->getBrandTid());
        $this->assertSame('67404', $transaction->getBrand()->getAuthorizationCode());
    }

    public function testShouldPopulateAuthorization(): void
    {
        $transaction = (new Transaction())->jsonUnserialize($this->getJsonAuthorizationMock());

        $this->assertNull($transaction->getBrand());

        $this->assertIsObject($transaction->getAuthorization());
        $this->assertSame('Approved', $transaction->getAuthorization()->getStatus());
        $this->assertSame(20099, $transaction->getAuthorization()->getAmount());
        $this->assertSame('12345678', $transaction->getAuthorization()->getTid());
        $this->assertSame('306718396', $transaction->getAuthorization()->getNsu());
        $this->assertSame('544828', $transaction->getAuthorization()->getCardBin());
        $this->assertSame('0007', $transaction->getAuthorization()->getLast4());
        $this->assertSame('00', $transaction->getAuthorization()->getReturnCode());
        $this->assertSame('Credit', $transaction->getAuthorization()->getKind());
        $this->assertSame('2025-06-02T13:15:39-03:00', $transaction->getAuthorization()->getDateTime()?->format('c'));
        $this->assertSame('John Snow', $transaction->getAuthorization()->getCardHolderName());
        $this->assertSame('1', $transaction->getAuthorization()->getOrigin());
        $this->assertEmpty($transaction->getAuthorization()->getSubscription());

        $this->assertIsObject($transaction->getAuthorization()->getBrand());
        $this->assertSame('Mastercard', $transaction->getAuthorization()->getBrand()->getName());
        $this->assertSame('Success.', $transaction->getAuthorization()->getBrand()->getReturnMessage());
        $this->assertSame('00', $transaction->getAuthorization()->getBrand()->getReturnCode());
        $this->assertSame('MCS1616339888484', $transaction->getAuthorization()->getBrand()->getBrandTid());
        $this->assertSame('67404', $transaction->getAuthorization()->getBrand()->getAuthorizationCode());

        $this->assertIsObject($transaction->getCapture());
        $this->assertSame('2025-06-02T13:15:39-03:00', $transaction->getCapture()->getDateTime()?->format('c'));
        $this->assertSame(20099, $transaction->getCapture()->getAmount());
        $this->assertSame('306718396', $transaction->getCapture()->getNsu());
    }

    public function testGetAuthorizationCodeValid(): void
    {
        // Cenário 1: AuthorizationCode diretamente na transação
        $transaction = (new Transaction())->jsonUnserialize($this->serializeJsonTransaction([
            'authorizationCode' => '111111',
        ]));
        $this->assertSame('111111', $transaction->getFirstAuthorizationCode());

        // Cenário 2: AuthorizationCode no Brand
        $transaction = (new Transaction())->jsonUnserialize($this->serializeJsonTransaction([
            'authorizationCode' => null,
            'brand' => [
                'authorizationCode' => '222222',
            ],
        ]));
        $this->assertSame('222222', $transaction->getFirstAuthorizationCode());

        // Cenário 3: AuthorizationCode no Authorization
        $transaction = (new Transaction())->jsonUnserialize($this->serializeJsonTransaction([
            'brand' => null,
            'authorization' => [
                'authorizationCode' => '333333',
            ],
        ]));
        $this->assertSame('333333', $transaction->getFirstAuthorizationCode());

        // Cenário 4: AuthorizationCode no Brand dentro do Authorization
        $transaction = (new Transaction())->jsonUnserialize($this->serializeJsonTransaction([
            'authorization' => [
                'brand' => [
                    'authorizationCode' => '444444',
                ],
            ],
        ]));
        $this->assertSame('444444', $transaction->getFirstAuthorizationCode());

        // Cenário 5: Nenhum código de autorização disponível
        $transaction = (new Transaction())->jsonUnserialize($this->serializeJsonTransaction([
            'authorization' => null,
            'brand' => null,
            'authorizationCode' => null,
        ]));
        $this->assertNull($transaction->getFirstAuthorizationCode());
    }

    private function serializeJsonTransaction(mixed $body): string
    {
        return json_encode($body) ?: '{}';
    }

    private function getJsonTransactionMock(): string
    {
        return '
            {
                "reference": "683dce2f41d8a",
                "tid": "12345678",
                "nsu": "306718396",
                "dateTime": "2025-06-02T13:15:39-03:00",
                "amount": 20099,
                "cardBin": "544828",
                "last4": "0007",
                "brand": {
                    "returnCode": "00",
                    "brandTid": "MCS1616339888484",
                    "authorizationCode": "67404",
                    "name": "Mastercard",
                    "returnMessage": "Success."
                },
                "returnCode": "00",
                "returnMessage": "Success.",
                "links": [
                    {
                    "method": "GET",
                    "rel": "transaction",
                    "href": "https://sandbox-erede.useredecloud.com.br/v1/transactions/12345678"
                    },
                    {
                    "method": "POST",
                    "rel": "refund",
                    "href": "https://sandbox-erede.useredecloud.com.br/v1/transactions/12345678/refunds"
                    }
                ]
            }
        ';
    }

    private function getJsonAuthorizationMock(): string
    {
        return '
            {
                "requestDateTime": "2025-06-02T13:15:40-03:00",
                "authorization": {
                    "dateTime": "2025-06-02T13:15:39-03:00",
                    "returnCode": "00",
                    "returnMessage": "Success.",
                    "affiliation": 38421438,
                    "status": "Approved",
                    "reference": "683dce2f41d8a",
                    "tid": "12345678",
                    "nsu": "306718396",
                    "kind": "Credit",
                    "amount": 20099,
                    "installments": 0,
                    "cardHolderName": "John Snow",
                    "cardBin": "544828",
                    "last4": "0007",
                    "origin": 1,
                    "subscription": false,
                    "brand": {
                    "name": "Mastercard",
                    "returnMessage": "Success.",
                    "returnCode": "00",
                    "brandTid": "MCS1616339888484",
                    "authorizationCode": "67404"
                    }
                },
                "capture": {
                    "dateTime": "2025-06-02T13:15:39-03:00",
                    "nsu": "306718396",
                    "amount": 20099
                },
                "links": [
                    {
                    "method": "GET",
                    "rel": "refunds",
                    "href": "https://sandbox-erede.useredecloud.com.br/v1/transactions/12345678/refunds"
                    },
                    {
                    "method": "POST",
                    "rel": "refund",
                    "href": "https://sandbox-erede.useredecloud.com.br/v1/transactions/12345678/refunds"
                    }
                ]
            }
        ';
    }
}
