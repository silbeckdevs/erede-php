<?php

namespace Rede\Tests\E2E;

use PHPUnit\Framework\Attributes\Depends;
use Rede\Device;
use Rede\QrCode;
use Rede\SubMerchant;
use Rede\ThreeDSecure;
use Rede\Transaction;
use Rede\Url;

class ERedeIntegrationTest extends BaseIntegrationTestCase
{
    public function testShouldAuthorizeACreditcardTransaction(): void
    {
        $transaction = (new Transaction(200.99, $this->generateReferenceNumber()))->creditCard(
            '5448280000000007',
            '235',
            '12',
            (int) date('Y') + 1,
            'John Snow'
        )->capture(false);

        $transaction = $this->createERede()->create($transaction);

        $this->assertEquals('00', $transaction->getReturnCode());
    }

    public function testShouldAuthorizeAndCaptureACreditcardTransaction(): void
    {
        $transaction = (new Transaction(200.99, $this->generateReferenceNumber()))->creditCard(
            '5448280000000007',
            '235',
            '12',
            (int) date('Y') + 1,
            'John Snow'
        )->capture();

        $transaction = $this->createERede()->create($transaction);

        $this->assertEquals('00', $transaction->getReturnCode());
    }

    public function testShouldAuthorizeACreditcardTransactionWithInstallments(): void
    {
        $transaction = (new Transaction(200.99, $this->generateReferenceNumber()))->creditCard(
            '5448280000000007',
            '235',
            '12',
            (int) date('Y') + 1,
            'John Snow'
        )->setInstallments(3);

        $transaction = $this->createERede()->create($transaction);

        $this->assertEquals('00', $transaction->getReturnCode());
    }

    public function testShouldAuthorizeACreditcardTransactionWithSoftdescriptor(): void
    {
        $transaction = (new Transaction(200.99, $this->generateReferenceNumber()))->creditCard(
            '5448280000000007',
            '235',
            '12',
            (int) date('Y') + 1,
            'John Snow'
        )->setSoftDescriptor('Loja X');

        $transaction = $this->createERede()->create($transaction);

        $this->assertEquals('00', $transaction->getReturnCode());
    }

    public function testShouldAuthorizeACreditcardTransactionWithAdditionalGatewayAndModuleInformation(): void
    {
        $transaction = (new Transaction(200.99, $this->generateReferenceNumber()))->creditCard(
            '5448280000000007',
            '235',
            '12',
            (int) date('Y') + 1,
            'John Snow'
        )->additional(1234, 56);

        $transaction = $this->createERede()->create($transaction);

        $this->assertEquals('00', $transaction->getReturnCode());
    }

    public function testShouldAuthorizeACreditcardTransactionWithDynamicMCC(): void
    {
        $transaction = (new Transaction(200.99, $this->generateReferenceNumber()))->creditCard(
            '5448280000000007',
            '235',
            '12',
            (int) date('Y') + 1,
            'John Snow'
        )->mcc(
            'LOJADOZE',
            '22349202212',
            new SubMerchant(
                '1234',
                'São Paulo',
                'Brasil'
            )
        );

        $transaction = $this->createERede()->create($transaction);

        $this->assertEquals('00', $transaction->getReturnCode());
    }

    public function testShouldAuthorizeACreditcardTransactionWithIATA(): void
    {
        $transaction = (new Transaction(200.99, $this->generateReferenceNumber()))->creditCard(
            '5448280000000007',
            '235',
            '12',
            (int) date('Y') + 1,
            'John Snow'
        )->iata('101010', '250');

        $transaction = $this->createERede()->create($transaction);

        $this->assertEquals('00', $transaction->getReturnCode());
    }

    public function testShouldAuthorizeAZeroDolarCreditcardTransaction(): void
    {
        $transaction = (new Transaction(0, $this->generateReferenceNumber()))->creditCard(
            '5448280000000007',
            '235',
            '12',
            (int) date('Y') + 1,
            'John Snow'
        )->setSoftDescriptor('Loja X');

        $transaction = $this->createERede()->zero($transaction);

        $this->assertEquals('174', $transaction->getReturnCode());
    }

    public function testShouldCreateADebitcardTransactionWithAuthentication(): void
    {
        $transaction = (new Transaction(25, $this->generateReferenceNumber()))->debitCard(
            '4514166653413658',
            '123',
            '12',
            (int) date('Y') + 1,
            'John Snow'
        );

        $transaction->threeDSecure(
            new Device(
                colorDepth: 1,
                deviceType3ds: 'BROWSER',
                javaEnabled: false,
                language: 'BR',
                screenHeight: 500,
                screenWidth: 500,
                timeZoneOffset: 3
            ),
            ThreeDSecure::DECLINE_ON_FAILURE
        );

        $transaction->addUrl('https://redirecturl.com/3ds/success', Url::THREE_D_SECURE_SUCCESS);
        $transaction->addUrl('https://redirecturl.com/3ds/failure', Url::THREE_D_SECURE_FAILURE);

        $transaction = $this->createERede()->create($transaction);
        $returnCode = $transaction->getReturnCode();

        $this->assertContains($returnCode, ['220', '201']);

        if ('220' === $returnCode) {
            $this->assertNotEmpty($transaction->getThreeDSecure()->getUrl());

            printf("\tURL de autenticação: %s\n", $transaction->getThreeDSecure()->getUrl());
        }
    }

    public function testShouldCaptureATransaction(): void
    {
        // First we create a new transaction
        $authorizedTransaction = $this->createERede()->create(
            (new Transaction(200.99, $this->generateReferenceNumber()))->creditCard(
                '5448280000000007',
                '235',
                '12',
                (int) date('Y') + 1,
                'John Snow'
            )->capture(false)
        );

        // Then we capture the authorized transaction
        $capturedTransaction = $this->createERede()
            ->capture($authorizedTransaction);

        $this->assertEquals('00', $authorizedTransaction->getReturnCode());
        $this->assertEquals('00', $capturedTransaction->getReturnCode());
    }

    public function testShouldCancelATransaction(): void
    {
        // First we create a new transaction
        $authorizedTransaction = $this->createAnAuthorizedTransaction();

        $this->assertEquals('00', $authorizedTransaction->getReturnCode());

        // Then we capture the authorized transaction
        $canceledTransaction = $this->createERede()
            ->cancel((new Transaction(200.99))
                ->setTid((string) $authorizedTransaction->getTid()));

        $this->assertEquals('359', $canceledTransaction->getReturnCode());
    }

    public function testShouldConsultATransactionByItsTID(): void
    {
        // First we create a new transaction
        $authorizedTransaction = $this->createAnAuthorizedTransaction();
        $contultedTransaction = $this->createERede()->get((string) $authorizedTransaction->getTid());
        $authorization = $contultedTransaction->getAuthorization();

        if (null === $authorization) {
            throw new \RuntimeException('Something happened with the authorized transaction');
        }

        $this->assertEquals($authorizedTransaction->getTid(), $authorization->getTid());
    }

    public function testShouldConsultATransactionByReference(): void
    {
        // First we create a new transaction
        $authorizedTransaction = $this->createAnAuthorizedTransaction();
        $contultedTransaction = $this->createERede()->getByReference((string) $authorizedTransaction->getReference());
        $authorization = $contultedTransaction->getAuthorization();

        if (null === $authorization) {
            throw new \RuntimeException('Something happened with the authorized transaction');
        }

        $this->assertEquals($authorizedTransaction->getReference(), $authorization->getReference());
    }

    public function testShouldConsultTheTransactionRefunds(): void
    {
        // First we create a new transaction
        $authorizedTransaction = $this->createAnAuthorizedTransaction();

        $this->assertEquals('00', $authorizedTransaction->getReturnCode());

        // Them we cancel the authorized transaction
        $canceledTransaction = $this->createERede()
            ->cancel((new Transaction(200.99))
                ->setTid((string) $authorizedTransaction->getTid()));

        $this->assertEquals('359', $canceledTransaction->getReturnCode());

        // Now we can consult the refunds
        $refundedTransactions = $this->createERede()->getRefunds((string) $authorizedTransaction->getTid());

        $this->assertCount(1, $refundedTransactions->getRefunds());

        foreach ($refundedTransactions->getRefunds() as $refund) {
            $this->assertEquals($canceledTransaction->getRefundId(), $refund->getRefundId());
        }
    }

    private function createAnAuthorizedTransaction(): Transaction
    {
        return $this->createERede()->create(
            (new Transaction(200.99, $this->generateReferenceNumber()))->creditCard(
                '5448280000000007',
                '235',
                12,
                (int) date('Y') + 1,
                'John Snow'
            )->capture()
        );
    }

    public function testShouldCreatePixTransaction(): Transaction
    {
        $transaction = (new Transaction(200.99, $this->generateReferenceNumber()))->createQrCode(new \DateTimeImmutable('+ 1 hour'));

        $transaction = $this->createERede()->create($transaction);

        $this->assertEquals('00', $transaction->getReturnCode());
        $this->assertInstanceOf(QrCode::class, $transaction->getQrCode());
        $this->assertNotEmpty($transaction->getQrCode()->getDateTimeExpiration());
        $this->assertNotEmpty($transaction->getQrCode()->getQrCodeImage());
        $this->assertNotEmpty($transaction->getQrCode()->getQrCodeData());

        return $transaction;
    }

    #[Depends('testShouldCreatePixTransaction')]
    public function testShouldGetPixTransaction(Transaction $tr): Transaction
    {
        $transaction = $this->createERede()->get($tr->getTid() ?? '');
        $this->assertInstanceOf(QrCode::class, $transaction->getQrCode());
        $this->assertNotEmpty($transaction->getQrCode()->getQrCodeImage());
        $this->assertNotEmpty($transaction->getQrCode()->getQrCodeData());
        $this->assertNotEmpty($transaction->getQrCode()->getExpirationQrCode());
        $this->assertSame('Pending', $transaction->getQrCode()->getStatus());
        $this->assertSame(20099, $transaction->getQrCode()->getAmount());

        return $transaction;
    }
}
