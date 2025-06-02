# SDK PHP

SDK de integração eRede

## Funcionalidades

Este SDK possui as seguintes funcionalidades:

- Autorização
- Captura
- Consultas
- Cancelamento
- 3DS2
- Zero dollar
- iata
- MCC dinâmico.
- PIX

## Dependências

- PHP >= 8.2

## Instalando o SDK

Se já possui um arquivo `composer.json`, basta adicionar a seguinte dependência ao seu projeto:

```json
{
  "require": {
    "silbeckdevs/erede-php": "*"
  }
}
```

Com a dependência adicionada ao `composer.json`, basta executar:

```bash
composer install
```

Alternativamente, você pode executar diretamente em seu terminal:

```bash
composer require "silbeckdevs/erede-php"
```

## Comandos

- Rodar todos os testes e PHPStan `composer test`
- Rodar todos os testes `composer phpunit`
- Testes unitários `composer test:unit`
- Testes integração `composer test:e2e`
- PHPStan `composer phpstan`
- PHP-CS-Fixer verify `composer format:check`
- PHP-CS-Fixer fix `composer format:fix`

## Testes

O SDK utiliza PHPUnit com TestDox para os testes. Para executá-los em ambiente local, você precisa exportar
as variáveis de ambiente `REDE_PV` e `REDE_TOKEN` com suas credenciais da API. Feito isso, basta rodar:

```bash
export REDE_PV=1234
export REDE_TOKEN=5678
export REDE_DEBUG=0
```

Ou copie o arquivo `tests/config/env.test.php.example` para `tests/config/env.test.php` e adicione as suas credenciais

## Autorizando uma transação

```php
<?php
// Configuração da loja em modo produção
$store = new Store('PV', 'TOKEN', Environment::production());

// Configuração da loja em modo sandbox
// $store = new \Rede\Store('PV', 'TOKEN', \Rede\Environment::sandbox());

// Transação que será autorizada
$transaction = (new Transaction(20.99, 'pedido' . time()))->creditCard(
    '5448280000000007',
    '235',
    '12',
    '2020',
    'John Snow'
);

// Autoriza a transação
$transaction = (new eRede($store))->create($transaction);

if ($transaction->getReturnCode() == '00') {
    printf("Transação autorizada com sucesso; tid=%s\n", $transaction->getTid());
}
```

Por padrão, a transação é capturada automaticamente; caso seja necessário apenas autorizar a transação, o método `Transaction::capture()` deverá ser chamado com o parâmetro `false`:

```php
<?php
// Configuração da loja em modo produção
$store = new Store('PV', 'TOKEN', Environment::production());

// Configuração da loja em modo sandbox
// $store = new \Rede\Store('PV', 'TOKEN', \Rede\Environment::sandbox());

// Transação que será autorizada
$transaction = (new Transaction(20.99, 'pedido' . time()))->creditCard(
    '5448280000000007',
    '235',
    '12',
    '2020',
    'John Snow'
)->capture(false);

// Autoriza a transação
$transaction = (new eRede($store))->create($transaction);

if ($transaction->getReturnCode() == '00') {
    printf("Transação autorizada com sucesso; tid=%s\n", $transaction->getTid());
}
//...
```

## Adiciona configuração de parcelamento

```php
<?php
// Configuração da loja em modo produção
$store = new Store('PV', 'TOKEN', Environment::production());

// Configuração da loja em modo sandbox
// $store = new \Rede\Store('PV', 'TOKEN', \Rede\Environment::sandbox());

// Transação que será autorizada
$transaction = (new Transaction(20.99, 'pedido' . time()))->creditCard(
    '5448280000000007',
    '235',
    '12',
    '2020',
    'John Snow'
);

// Configuração de parcelamento
$transaction->setInstallments(3);

// Autoriza a transação
$transaction = (new eRede($store))->create($transaction);

if ($transaction->getReturnCode() == '00') {
    printf("Transação autorizada com sucesso; tid=%s\n", $transaction->getTid());
}
```

## Adiciona informação adicional de gateway e módulo

```php
<?php
// Configuração da loja em modo produção
$store = new Store('PV', 'TOKEN', Environment::production());

// Configuração da loja em modo sandbox
// $store = new \Rede\Store('PV', 'TOKEN', \Rede\Environment::sandbox());

// Transação que será autorizada
$transaction = (new Transaction(20.99, 'pedido' . time()))->creditCard(
    '5448280000000007',
    '235',
    '12',
    '2020',
    'John Snow'
)->additional(1234, 56);

// Autoriza a transação
$transaction = (new eRede($store))->create($transaction);

if ($transaction->getReturnCode() == '00') {
    printf("Transação autorizada com sucesso; tid=%s\n", $transaction->getTid());
}
```

## Autorizando uma transação com MCC dinâmico

```php
<?php
// Configuração da loja em modo produção
$store = new Store('PV', 'TOKEN', Environment::production());

// Configuração da loja em modo sandbox
// $store = new \Rede\Store('PV', 'TOKEN', \Rede\Environment::sandbox());

// Transação que será autorizada
$transaction = (new Transaction(20.99, 'pedido' . time()))->creditCard(
    '5448280000000007',
    '235',
    '12',
    '2020',
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

// Autoriza a transação
$transaction = (new eRede($store))->create($transaction);

if ($transaction->getReturnCode() == '00') {
    printf("Transação autorizada com sucesso; tid=%s\n", $transaction->getTid());
}
//...
```

## Autorizando uma transação IATA

```php
<?php
// Configuração da loja em modo produção
$store = new Store('PV', 'TOKEN', Environment::production());

// Configuração da loja em modo sandbox
// $store = new \Rede\Store('PV', 'TOKEN', \Rede\Environment::sandbox());

// Transação que será autorizada
$transaction = (new Transaction(20.99, 'pedido' . time()))->creditCard(
    '5448280000000007',
    '235',
    '12',
    '2020',
    'John Snow'
)->iata('code123', '250');

// Autoriza a transação
$transaction = (new eRede($store))->create($transaction);

if ($transaction->getReturnCode() == '00') {
    printf("Transação autorizada com sucesso; tid=%s\n", $transaction->getTid());
}
```

## Capturando uma transação

```php
<?php
// Configuração da loja em modo produção
$store = new Store('PV', 'TOKEN', Environment::production());

// Configuração da loja em modo sandbox
// $store = new \Rede\Store('PV', 'TOKEN', \Rede\Environment::sandbox());

// Transação que será capturada
$transaction =  (new eRede($store))->capture((new Transaction(20.99))->setTid('TID123'));

if ($transaction->getReturnCode() == '00') {
    printf("Transação capturada com sucesso; tid=%s\n", $transaction->getTid());
}
```

## Cancelando uma transação

```php
<?php
// Configuração da loja em modo produção
$store = new Store('PV', 'TOKEN', Environment::production());

// Configuração da loja em modo sandbox
// $store = new \Rede\Store('PV', 'TOKEN', \Rede\Environment::sandbox());

// Transação que será cancelada
$transaction = (new eRede($store))->cancel((new Transaction(20.99))->setTid('TID123'));

if ($transaction->getReturnCode() == '359') {
    printf("Transação cancelada com sucesso; tid=%s\n", $transaction->getTid());
}
```

## Consultando uma transação pelo ID

```php
<?php
// Configuração da loja em modo produção
$store = new Store('PV', 'TOKEN', Environment::production());

// Configuração da loja em modo sandbox
// $store = new \Rede\Store('PV', 'TOKEN', \Rede\Environment::sandbox());

$transaction = (new eRede($store))->get('TID123');

printf("O status atual da autorização é %s\n", $transaction->getAuthorization()->getStatus());
```

## Consultando uma transação pela referência

```php
<?php
// Configuração da loja em modo produção
$store = new Store('PV', 'TOKEN', Environment::production());

// Configuração da loja em modo sandbox
// $store = new \Rede\Store('PV', 'TOKEN', \Rede\Environment::sandbox());

$transaction = (new eRede($store))->getByReference('pedido123');

printf("O status atual da autorização é %s\n", $transaction->getAuthorization()->getStatus());
```

## Consultando cancelamentos de uma transação

```php
<?php
// Configuração da loja em modo produção
$store = new Store('PV', 'TOKEN', Environment::production());

// Configuração da loja em modo sandbox
// $store = new \Rede\Store('PV', 'TOKEN', \Rede\Environment::sandbox());

$transaction = (new eRede($store))->getRefunds('TID123');

printf("O status atual da autorização é %s\n", $transaction->getAuthorization()->getStatus());
```

## Transação com autenticação

```php
<?php
// Configuração da loja em modo produção
$store = new Store('PV', 'TOKEN', Environment::production());

// Configuração da loja em modo sandbox
// $store = new \Rede\Store('PV', 'TOKEN', \Rede\Environment::sandbox());

// Configura a transação que será autorizada após a autenticação
$transaction = (new Transaction(25, 'pedido' . time()))->debitCard(
    '5277696455399733',
    '123',
    '01',
    '2020',
    'John Snow'
);

// Configura o 3dSecure para autenticação
$transaction->threeDSecure(
    new Device(
        colorDepth: 1,
        deviceType3ds: 'BROWSER',
        javaEnabled: false,
        language: 'BR',
        screenHeight: 500,
        screenWidth: 500,
        timeZoneOffset: 3
    )
);
$transaction->addUrl('https://redirecturl.com/3ds/success', Url::THREE_D_SECURE_SUCCESS);
$transaction->addUrl('https://redirecturl.com/3ds/failure', Url::THREE_D_SECURE_FAILURE);

$transaction = (new eRede($store))->create($transaction);

if ($transaction->getReturnCode() == '220') {
    printf("Redirecione o cliente para \"%s\" para autenticação\n", $transaction->getThreeDSecure()->getUrl());
}
```

## Transação com PIX

```php
<?php
// Configura a transação para o PIX e passa a data de expiração
$transaction = (new Transaction(200.99, 'pedido' . time()))->createQrCode(new \DateTimeImmutable('+ 1 hour'));

$transaction = (new eRede($store))->create($transaction);

if ($transaction->getReturnCode() == '00') {
    printf(
        "Transação criada com sucesso; tid=%s, qrCodeData=%s, qrCodeImage=%s\n",
        $transaction->getTid(), $transaction->getQrCode()->getQrCodeData(), $transaction->getQrCode()->getQrCodeImage()
    );
}
```

## Observações

- Ao criar uma transação com `$transaction = (new eRede($store))->create($transaction)` não vai retornar o campo `authorization`, para retornar o campo é preciso fazer uma consulta `$transaction = (new eRede($store))->get('TID123')`
- O campo `$transaction->getAuthorizationCode()` não está retornando nada, use `$transaction->getBrand()?->getAuthorizationCode()` ou `$transaction->getAuthorization()?->getBrand()?->getAuthorizationCode()`
- Caso precise acessar o JSON original do response utilize `$transaction?->getHttpResponse()->getBody()`
