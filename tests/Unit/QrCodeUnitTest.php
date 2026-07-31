<?php

namespace Rede\Tests\Unit;

use Rede\QrCode;
use Rede\Tests\BaseTestCase;

class QrCodeUnitTest extends BaseTestCase
{
    public function testShouldPopulateQrCode(): void
    {
        $body = new \stdClass();
        $body->dateTimeExpiration = '2026-11-12T15:00:09';
        // Campos que retornam apenas na consulta
        $body->dateTime = '2026-11-12T14:39:09';
        $body->returnCode = '00';
        $body->returnMessage = 'Success.';
        $body->affiliation = 38421438;
        $body->kind = 'Pix';
        $body->reference = '6733a0d967616';
        $body->amount = 9959;
        $body->tid = '40012411121539096004';
        $body->status = 'Pending';
        $body->expirationQrCode = '2026-11-12T15:00:09';
        $body->qrCodeData = '123';
        $body->qrCodeImage = '456';
        $body->origin = 1;
        $body->txId = 'REZ12345600384214382411121539095NP';

        $qrCode = (new QrCode())->populate($body);

        $this->assertSame('Pending', $qrCode->getStatus());
        $this->assertSame(9959, $qrCode->getAmount());
        $this->assertSame('123', $qrCode->getQrCodeData());
        $this->assertSame('456', $qrCode->getQrCodeImage());
        $this->assertSame('40012411121539096004', $qrCode->getTid());
        $this->assertSame('2026-11-12T15:00:09', $qrCode->getDateTimeExpiration()?->format(QrCode::DATE_TIME_FORMAT));
        $this->assertSame('2026-11-12T15:00:09', $qrCode->getExpirationQrCode()?->format(QrCode::DATE_TIME_FORMAT));
        $this->assertSame('2026-11-12T14:39:09', $qrCode->getDateTime()?->format(QrCode::DATE_TIME_FORMAT));

        $encoded = $qrCode->jsonSerialize();
        $this->assertSame('2026-11-12T15:00:09', $encoded['dateTimeExpiration']);
    }
}
