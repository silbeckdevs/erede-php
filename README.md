# SDK PHP

SDK de integração eRede

## ⚠️ Atualização Importante - Nova Autenticação

A partir de **janeiro de 2026**, a Rede implementou um novo método de autenticação baseado em **OAuth2** para aprimorar a segurança das transações.

**A versão 2.x deste SDK é compatível com o novo método de autenticação OAuth2**, garantindo uma transição suave e segura para os desenvolvedores.

Para mais detalhes sobre a nova autenticação e migração, consulte a [documentação oficial da e-Rede](https://developer.userede.com.br/e-rede).

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
    "silbeckdevs/erede-php": "^2.0.0"
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

## Configuração da loja

A configuração da loja é feita através da classe `Store`. Ela possui os seguintes parâmetros:

- `filiation`: Número de filiação do estabelecimento (ClientId na versão 2.x)
- `token`: Chave de Integração (ClientSecret na versão 2.x)
- `environment`: Ambiente da loja (Production ou Sandbox)

```php
<?php
// Configuração da loja em modo produção
$store = new Store('PV', 'TOKEN', Environment::production());

// Configuração da loja em modo sandbox
// $store = new \Rede\Store('PV', 'TOKEN', \Rede\Environment::sandbox());

$eRedeService = new \Rede\eRede($store);
```

## Autorizando uma transação

```php
<?php
// Transação que será autorizada
$transaction = (new Transaction(20.99, 'pedido' . time()))->creditCard(
    '5448280000000007',
    '235',
    '12',
    '2020',
    'John Snow'
);

// Autoriza a transação
$transaction = $eRedeService->create($transaction);

if ($transaction->getReturnCode() == '00') {
    printf("Transação autorizada com sucesso; tid=%s\n", $transaction->getTid());
}
```

Por padrão, a transação é capturada automaticamente; caso seja necessário apenas autorizar a transação, o método `Transaction::capture()` deverá ser chamado com o parâmetro `false`:

```php
<?php
// Transação que será autorizada
$transaction = (new Transaction(20.99, 'pedido' . time()))->creditCard(
    '5448280000000007',
    '235',
    '12',
    '2020',
    'John Snow'
)->capture(false);

// Autoriza a transação
$transaction = $eRedeService->create($transaction);

if ($transaction->getReturnCode() == '00') {
    printf("Transação autorizada com sucesso; tid=%s\n", $transaction->getTid());
}
//...
```

## Adiciona configuração de parcelamento

```php
<?php
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
$transaction = $eRedeService->create($transaction);

if ($transaction->getReturnCode() == '00') {
    printf("Transação autorizada com sucesso; tid=%s\n", $transaction->getTid());
}
```

## Adiciona informação adicional de gateway e módulo

```php
<?php
// Transação que será autorizada
$transaction = (new Transaction(20.99, 'pedido' . time()))->creditCard(
    '5448280000000007',
    '235',
    '12',
    '2020',
    'John Snow'
)->additional(1234, 56);

// Autoriza a transação
$transaction = $eRedeService->create($transaction);

if ($transaction->getReturnCode() == '00') {
    printf("Transação autorizada com sucesso; tid=%s\n", $transaction->getTid());
}
```

## Autorizando uma transação com MCC dinâmico

```php
<?php
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
$transaction = $eRedeService->create($transaction);

if ($transaction->getReturnCode() == '00') {
    printf("Transação autorizada com sucesso; tid=%s\n", $transaction->getTid());
}
//...
```

## Autorizando uma transação IATA

```php
<?php
// Transação que será autorizada
$transaction = (new Transaction(20.99, 'pedido' . time()))->creditCard(
    '5448280000000007',
    '235',
    '12',
    '2020',
    'John Snow'
)->iata('code123', '250');

// Autoriza a transação
$transaction = $eRedeService->create($transaction);

if ($transaction->getReturnCode() == '00') {
    printf("Transação autorizada com sucesso; tid=%s\n", $transaction->getTid());
}
```

## Capturando uma transação

```php
<?php
// Transação que será capturada
$transaction =  $eRedeService->capture((new Transaction(20.99))->setTid('TID123'));

if ($transaction->getReturnCode() == '00') {
    printf("Transação capturada com sucesso; tid=%s\n", $transaction->getTid());
}
```

## Cancelando uma transação

```php
<?php
// Transação que será cancelada
$transaction = $eRedeService->cancel((new Transaction(20.99))->setTid('TID123'));

if ($transaction->getReturnCode() == '359') {
    printf("Transação cancelada com sucesso; tid=%s\n", $transaction->getTid());
}
```

## Consultando uma transação pelo ID

```php
<?php
// Consulta a transação pelo ID
$transaction = $eRedeService->get('TID123');

printf("O status atual da autorização é %s\n", $transaction->getAuthorization()->getStatus());
```

## Consultando uma transação pela referência

```php
<?php
// Consulta a transação pela referência
$transaction = $eRedeService->getByReference('pedido123');

printf("O status atual da autorização é %s\n", $transaction->getAuthorization()->getStatus());
```

## Consultando cancelamentos de uma transação

```php
<?php
// Consulta os cancelamentos de uma transação
$transaction = $eRedeService->getRefunds('TID123');

printf("O status atual da autorização é %s\n", $transaction->getAuthorization()->getStatus());
```

## Transação com autenticação

```php
<?php
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

$transaction = $eRedeService->create($transaction);

if ($transaction->getReturnCode() == '220') {
    printf("Redirecione o cliente para \"%s\" para autenticação\n", $transaction->getThreeDSecure()->getUrl());
}
```

## Transação com PIX

```php
<?php
// Configura a transação para o PIX e passa a data de expiração
$transaction = (new Transaction(200.99, 'pedido' . time()))->createQrCode(new \DateTimeImmutable('+ 1 hour'));

$transaction = $eRedeService->create($transaction);

if ($transaction->getReturnCode() == '00') {
    printf(
        "Transação criada com sucesso; tid=%s, qrCodeData=%s, qrCodeImage=%s\n",
        $transaction->getTid(), $transaction->getQrCode()->getQrCodeData(), $transaction->getQrCode()->getQrCodeImage()
    );
}
```

## Observações

- Ao criar uma transação com `$transaction = $eRedeService->create($transaction)` não vai retornar o campo `authorization`, para retornar o campo é preciso fazer uma consulta `$transaction = $eRedeService->get('TID123')`
- O campo `$transaction->getAuthorizationCode()` não está retornando nada, use `$transaction->getBrand()?->getAuthorizationCode()` ou `$transaction->getAuthorization()?->getBrand()?->getAuthorizationCode()`
- Caso precise acessar o JSON original do response utilize `$transaction?->getHttpResponse()->getBody()`

### Gerenciamento de Token OAuth2

O token de autenticação OAuth2 possui um **tempo de expiração** de 24 minutos. Para otimizar o desempenho e evitar requisições desnecessárias, é recomendado **salvar e reutilizar o token** enquanto ele estiver válido.

**Exemplo de implementação:**

```php
<?php
$store = new Store('PV', 'TOKEN', Environment::production());
$eRedeService = new eRede($store);

// Faça suas requisições...

// Salve o token para reutilização e salve em um local seguro
$cachedToken = json_encode($eRedeService->getOAuthToken());

// Para reutilizar o token, basta decodificar o JSON e setar no store
$store->setOAuthToken((new OAuthToken())->populate(json_decode($cachedToken)));
$eRedeService = new eRede($store);
```

**Recomendações:**

- Armazene o token em cache (Redis, Memcached) ou banco de dados para ambientes de produção
- Sempre verifique a expiração antes de reutilizar o token
- O SDK gerencia automaticamente a renovação quando o token expira
