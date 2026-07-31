# AGENTS.md

## Contexto do projeto

- Projeto: `silbeckdevs/erede-php`.
- Tipo: SDK/biblioteca PHP para integração com a [API e.Rede](https://developer.userede.com.br/e-rede).
- Namespace principal: `Rede\` (autoload PSR-4 em `src/Rede/`).
- Ponto de entrada: `Rede\eRede`.
- Autenticação: OAuth 2.0 (`client_credentials`); `filiation`/`token` da `Store` correspondem a ClientId/ClientSecret.

## Stack e versoes

- PHP: `^8.2` (CI testa em 8.2 e 8.3).
- Extensoes obrigatorias: `curl`, `json`.
- Composer: `^2.x`.
- Dependencias: `psr/log` (logging opcional via `LoggerInterface`).
- Qualidade: PHPUnit 11/12, PHPStan 1/2, PHP-CS-Fixer 3 (dev).
- Git hooks: pre-commit executa `composer format:check`.

## Estrutura do codigo

- `src/Rede/eRede.php`: facade principal (`create`, `capture`, `cancel`, `get`, `getByReference`, `getRefunds`, `zero`, `generateOAuthToken`).
- `src/Rede/Store.php`: credenciais da loja (`filiation`, `token`, `environment`, `oauthToken`).
- `src/Rede/Environment.php`: ambiente sandbox/producao e URLs da API (`v2`).
- `src/Rede/OAuthToken.php`: token OAuth2 com validade e serializacao.
- `src/Rede/Transaction.php`: transacao com builder fluente (credito, debito, PIX, 3DS, MCC, IATA, etc.).
- `src/Rede/Authorization.php`, `Brand.php`, `Capture.php`, `Refund.php`, `QrCode.php`, `ThreeDSecure.php`, `Device.php`, `SubMerchant.php`, `Iata.php`, `Additional.php`, `Cart.php`, `Item.php`, `Consumer.php`, `Address.php`, `Phone.php`, `Url.php`, `Flight.php`, `Passenger.php`: modelos de dominio.
- `src/Rede/RedeSerializable.php`, `RedeUnserializable.php`, `SerializeTrait.php`, `ResponseTrait.php`, `CreateTrait.php`: contratos e traits de serializacao.
- `src/Rede/Http/RedeHttpClient.php`: base HTTP (cURL, TLS 1.2, Bearer token, mascaramento de cartao nos logs).
- `src/Rede/Http/RedeResponse.php`: encapsula status HTTP e corpo da resposta.
- `src/Rede/Service/OAuthService.php`: obtencao do access token.
- `src/Rede/Service/CreateTransactionService.php`: autorizacao/criacao de transacao.
- `src/Rede/Service/CaptureTransactionService.php`: captura (PUT).
- `src/Rede/Service/CancelTransactionService.php`: cancelamento/estorno (POST em `/refunds`).
- `src/Rede/Service/GetTransactionService.php`: consulta por TID, referencia ou refunds.
- `src/Rede/Service/AbstractService.php`, `AbstractTransactionsService.php`: base dos servicos HTTP.
- `src/Rede/Exception/RedeException.php`: excecao de erros da API.
- `tests/Unit/*`: testes unitarios (namespace `Rede\Tests\Unit\`).
- `tests/E2E/*`: testes de integracao com API sandbox (namespace `Rede\Tests\E2E\`; exigem credenciais).

## Padroes de implementacao

- `eRede` delega operacoes para classes em `Service/`; nao colocar logica HTTP diretamente na facade.
- Servicos estendem `AbstractService` (que estende `RedeHttpClient`) e implementam `execute()`.
- Operacoes de transacao usam `AbstractTransactionsService` para serializar `Transaction` em JSON e parsear a resposta.
- `Transaction` implementa `RedeSerializable` e `RedeUnserializable` para montar payloads e desserializar respostas.
- Builders fluentes em `Transaction` (`->creditCard(...)`, `->debitCard(...)`, `->createQrCode(...)`, `->threeDSecure(...)`, etc.).
- Requisicoes usam cURL com TLS 1.2, header `Authorization: Bearer` e `User-Agent` identificando o SDK.
- O token OAuth e gerado automaticamente no construtor de `eRede` quando ausente ou invalido; recomenda-se cachear e reutilizar via `Store::setOAuthToken()`.
- Logging via `Psr\Log\LoggerInterface` e opcional; quando presente, dados de cartao sao mascarados em `RedeHttpClient`.
- Preserve compatibilidade retroativa: e um SDK publicado consumido por aplicacoes de pagamento.

## Diretrizes para alteracoes

- Evite quebrar assinaturas publicas sem justificativa clara e sem atualizar testes.
- Para novas operacoes ou meios de pagamento, siga o padrao existente: modelo de dominio + servico + metodo em `eRede`.
- Mantenha coesao por modulo (`Http`, `Service`, modelos de dominio).
- Consulte a [documentacao oficial da e.Rede](https://developer.userede.com.br/e-rede) para campos, codigos de retorno e fluxos (3DS, PIX, etc.).
- O SDK monta transacoes; redirecionamento do usuario (3DS) fica a cargo da aplicacao consumidora.
- Apos `create()`, o campo `authorization` pode nao vir preenchido; use `get()` para obter dados completos da transacao.

## Qualidade e convencoes

- Arquivos em UTF-8 e quebra de linha `LF`.
- Indentacao: 4 espacos.
- Convencoes: classe `PascalCase`, metodo/variavel `camelCase`, constante `SCREAMING_SNAKE_CASE`.
- Analise estatica: `phpstan.neon` (nivel 8, PHP 8.2).
- Sempre adicionar/atualizar testes quando alterar comportamento publico.
- Toda alteracao deve terminar com analise estatica e testes antes de concluir a tarefa.

## Comandos relevantes

- Validacao completa local: `composer test` (PHPStan + PHPUnit).
- PHPStan: `composer phpstan`
- Checar formato: `composer format:check`
- Corrigir formato: `composer format:fix`
- Lint padrao do projeto: `composer lint` (format:fix + phpstan)
- PHPUnit (todos os testes): `composer phpunit`
- Testes unitarios: `composer test:unit`
- Testes E2E: `composer test:e2e` (requer `REDE_PV` e `REDE_TOKEN`)
- Cobertura: `composer test:coverage`
- Validar `composer.json`: `composer validate --strict`
- Auditoria de seguranca: `composer audit --no-dev`

## Seguranca e limites

- Nunca commitar credenciais: `REDE_PV`, `REDE_TOKEN`, tokens OAuth, dados de cartao reais.
- Nunca registrar CVV, numero completo de cartao ou chaves em logs, mensagens de erro ou dumps de debug.
- O SDK ja mascara `cardHolderName`, `cardnumber` e `securitycode` em logs de debug; preserve esse comportamento ao alterar `RedeHttpClient`.
- Evitar alterar `vendor/` e arquivos gerados automaticamente.
- Em erros/excecoes, evitar expor respostas brutas da API com dados sensiveis ao usuario final.
- Testes E2E chamam a API sandbox real e devem rodar apenas com credenciais configuradas (`tests/config/env.test.php` ou variaveis de ambiente).
- Validar entradas de modelos (valores monetarios, bandeiras, tipos de pagamento) ao adicionar novos campos.

## Testes e validacao final (obrigatorio)

- Ao finalizar qualquer alteracao, executar obrigatoriamente:
  - `composer phpstan`
  - `composer test:unit`
- Para alteracoes que impactam integracao HTTP ou fluxos de pagamento, rodar tambem `composer test:e2e` quando houver credenciais disponiveis.
- Se houver falha em qualquer comando, corrigir e rodar novamente ate passar.
- Nao considerar tarefa concluida sem evidenciar que analise estatica e testes passaram.

## Commits (obrigatorio)

Usar Conventional Commits em ingles (en-US):

- `<tipo>(<escopo>): <mensagem curta em en-US>`
- Tipos: `feat`, `fix`, `refactor`, `chore`, `docs`, `style`, `perf`, `test`, `build`, `ci`, `revert`

Exemplos:

- `feat(transaction): adicionar suporte a novo meio de pagamento`
- `fix(http): corrigir mascaramento de cartao nos logs de debug`
- `test(unit): cobrir serializacao de token OAuth`
