<?php

namespace Rede\Tests\E2E;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Rede\Environment;
use Rede\eRede;
use Rede\Store;
use Rede\Tests\BaseTestCase;

abstract class BaseIntegrationTestCase extends BaseTestCase
{
    private ?Store $store = null;

    private ?LoggerInterface $logger = null;

    protected static int $sequence = 1;

    protected function setUp(): void
    {
        $filiation = getenv('REDE_PV');
        $token = getenv('REDE_TOKEN');
        $debug = (int) getenv('REDE_DEBUG');

        if (empty($filiation) || empty($token)) {
            throw new \RuntimeException('Você precisa informar seu PV e Token para rodar os testes');
        }

        $this->logger = new Logger('eRede SDK Test');
        $this->logger->pushHandler(new StreamHandler('php://stdout', $debug ? Level::Debug : Level::Error));

        $this->store = new Store($filiation, $token, Environment::sandbox());
    }

    protected function generateReferenceNumber(): string
    {
        return 'pedido' . (time() + self::$sequence++);
    }

    protected function createERede(): eRede
    {
        if (null === $this->store) {
            throw new \RuntimeException('Store cant be null');
        }

        return new eRede($this->store, $this->logger);
    }
}
