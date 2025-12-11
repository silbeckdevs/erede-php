<?php

namespace Rede\Tests\Unit;

use Rede\Environment;
use Rede\eRede;
use Rede\OAuthToken;
use Rede\Store;
use Rede\Tests\BaseTestCase;

class AuthUnitTest extends BaseTestCase
{
    public function testAccessTokenAllFeatures(): void
    {
        $oauthToken = (new OAuthToken())
            ->setTokenType('Bearer')
            ->setAccessToken('fluent_token_xyz')
            ->setExpiresIn(7200)
            ->setScope('admin')
            ->setExpiresAt(time() + 7200);

        $this->assertSame('Bearer', $oauthToken->getTokenType());
        $this->assertSame('fluent_token_xyz', $oauthToken->getAccessToken());
        $this->assertSame(7200, $oauthToken->getExpiresIn());
        $this->assertSame('admin', $oauthToken->getScope());
        $this->assertNotNull($oauthToken->getExpiresAt());

        $emptyToken = new OAuthToken();
        $this->assertFalse($emptyToken->isValid());

        $validTokenNoExpiry = (new OAuthToken())
            ->setAccessToken('valid_token_no_expiry');
        $this->assertTrue($validTokenNoExpiry->isValid());

        $validToken = (new OAuthToken())
            ->setAccessToken('valid_token_with_expiry')
            ->setExpiresAt(time() + 1800); // expira em 30 minutos
        $this->assertTrue($validToken->isValid());

        $expiredToken = (new OAuthToken())
            ->setAccessToken('expired_token')
            ->setExpiresAt(time() - 100); // expirou há 100 segundos
        $this->assertFalse($expiredToken->isValid());

        $expiringNowToken = (new OAuthToken())
            ->setAccessToken('expiring_now_token')
            ->setExpiresAt(time()); // expira agora
        $this->assertFalse($expiringNowToken->isValid());

        $jsonData = (object) [
            'token_type' => 'Bearer',
            'access_token' => 'populated_token_abc',
            'expires_in' => 3600,
            'scope' => 'full_access',
        ];

        $populatedToken = (new OAuthToken())->populate($jsonData);
        $this->assertSame('Bearer', $populatedToken->getTokenType());
        $this->assertSame('populated_token_abc', $populatedToken->getAccessToken());
        $this->assertSame(3600, $populatedToken->getExpiresIn());
        $this->assertSame('full_access', $populatedToken->getScope());

        $partialJsonData = (object) [
            'token_type' => 'Bearer',
            'access_token' => 'partial_token',
            'expires_in' => null,
            'scope' => null,
        ];

        $partialToken = (new OAuthToken())->populate($partialJsonData);
        $this->assertSame('Bearer', $partialToken->getTokenType());
        $this->assertSame('partial_token', $partialToken->getAccessToken());
        $this->assertNull($partialToken->getExpiresIn());
        $this->assertNull($partialToken->getScope());

        $tokenForArray = (new OAuthToken())
            ->setTokenType('Bearer')
            ->setAccessToken('array_test_token')
            ->setExpiresIn(1800);

        $array = $tokenForArray->toArray();
        $this->assertIsArray($array);
        $this->assertArrayHasKey('token_type', $array);
        $this->assertArrayHasKey('access_token', $array);
        $this->assertArrayHasKey('expires_in', $array);
        $this->assertArrayNotHasKey('scope', $array); // null não deve estar no array
        $this->assertArrayNotHasKey('expires_at', $array); // null não deve estar no array
        $this->assertSame('Bearer', $array['token_type']);
        $this->assertSame('array_test_token', $array['access_token']);
        $this->assertSame(1800, $array['expires_in']);

        $tokenForJson = (new OAuthToken())
            ->setTokenType('Bearer')
            ->setAccessToken('json_test_token')
            ->setExpiresIn(3600)
            ->setScope('read');

        $jsonArray = $tokenForJson->jsonSerialize();
        $this->assertIsArray($jsonArray);
        $this->assertSame('Bearer', $jsonArray['token_type']);
        $this->assertSame('json_test_token', $jsonArray['access_token']);
        $this->assertSame(3600, $jsonArray['expires_in']);
        $this->assertSame('read', $jsonArray['scope']);

        $oauthResponse = (object) [
            'token_type' => 'Bearer',
            'access_token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9',
            'expires_in' => 3600,
            'scope' => 'api_access payment_processing',
        ];

        $realToken = (new OAuthToken())->populate($oauthResponse);
        $realToken->setExpiresAt(time() + $realToken->getExpiresIn() - 60); // como no OAuthService

        $this->assertTrue($realToken->isValid());
        $this->assertSame('Bearer', $realToken->getTokenType());
        $this->assertSame('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9', $realToken->getAccessToken());
        $this->assertSame(3600, $realToken->getExpiresIn());
        $this->assertSame('api_access payment_processing', $realToken->getScope());
        $this->assertNotNull($realToken->getExpiresAt());
        $this->assertGreaterThan(time(), $realToken->getExpiresAt());

        $resetToken = (new OAuthToken())
            ->setTokenType('Bearer')
            ->setAccessToken('reset_test')
            ->setExpiresIn(3600)
            ->setScope('test')
            ->setExpiresAt(time() + 3600);

        // Reset dos valores
        $resetToken->setTokenType(null)
            ->setAccessToken(null)
            ->setExpiresIn(null)
            ->setScope(null)
            ->setExpiresAt(null);

        $this->assertNull($resetToken->getTokenType());
        $this->assertNull($resetToken->getAccessToken());
        $this->assertNull($resetToken->getExpiresIn());
        $this->assertNull($resetToken->getScope());
        $this->assertNull($resetToken->getExpiresAt());
        $this->assertFalse($resetToken->isValid());

        $emptyStringToken = (new OAuthToken())
            ->setAccessToken('')
            ->setExpiresAt(time() + 3600);
        $this->assertFalse($emptyStringToken->isValid()); // string vazia deve ser inválida

        $zeroExpiryToken = (new OAuthToken())
            ->setAccessToken('valid_token')
            ->setExpiresAt(0);
        $this->assertFalse($zeroExpiryToken->isValid()); // 0 está no passado
    }

    public function testOAuthTokenStore(): void
    {
        $store = new Store('1234567890', '1234567890');
        $this->assertNull($store->getOAuthToken());

        $token = (new OAuthToken())->setAccessToken('test_token_123');
        $store->setOAuthToken($token);

        $this->assertInstanceOf(OAuthToken::class, $store->getOAuthToken());
        $this->assertSame('test_token_123', $store->getOAuthToken()->getAccessToken());
        $this->assertTrue($store->getOAuthToken()->isValid());

        $eRedeService = new eRede($store);
        $this->assertSame($token, $eRedeService->getOAuthToken());
    }

    public function testEnvironmentAllFeatures(): void
    {
        $productionEnv = Environment::production();
        $sandboxEnv = Environment::sandbox();

        $this->assertInstanceOf(Environment::class, $productionEnv);
        $this->assertInstanceOf(Environment::class, $sandboxEnv);
        $this->assertTrue($productionEnv->isProduction());
        $this->assertFalse($sandboxEnv->isProduction());

        $this->assertSame('https://api.userede.com.br/erede/v2/', $productionEnv->getBaseUrl());
        $this->assertSame('https://sandbox-erede.useredecloud.com.br/v2/', $sandboxEnv->getBaseUrl());

        $this->assertSame('https://api.userede.com.br/redelabs', $productionEnv->getBaseUrlOAuth());
        $this->assertSame('https://rl7-sandbox-api.useredecloud.com.br', $sandboxEnv->getBaseUrlOAuth());

        $this->assertSame('https://api.userede.com.br/erede/v2/transactions', $productionEnv->getEndpoint('transactions'));
        $this->assertSame('https://sandbox-erede.useredecloud.com.br/v2/transactions', $sandboxEnv->getEndpoint('transactions'));

        $this->assertSame('https://api.userede.com.br/erede/v2/transactions/123/capture', $productionEnv->getEndpoint('transactions/123/capture'));
        $this->assertSame('https://sandbox-erede.useredecloud.com.br/v2/transactions/456/refunds', $sandboxEnv->getEndpoint('transactions/456/refunds'));
        $this->assertSame('https://api.userede.com.br/erede/v2/transactions/789/cancellations', $productionEnv->getEndpoint('transactions/789/cancellations'));

        $this->assertSame('https://api.userede.com.br/erede/v2/', $productionEnv->getEndpoint(''));
        $this->assertSame('https://sandbox-erede.useredecloud.com.br/v2//test', $sandboxEnv->getEndpoint('/test'));

        $this->assertSame('https://api.userede.com.br/erede', Environment::PRODUCTION);
        $this->assertSame('https://sandbox-erede.useredecloud.com.br', Environment::SANDBOX);
        $this->assertSame('v2', Environment::VERSION);

        $envWithData = Environment::production()->setIp('192.168.1.1')->setSessionId('session123');
        $jsonData = $envWithData->jsonSerialize();

        $this->assertIsArray($jsonData);
        $this->assertArrayHasKey('consumer', $jsonData);
        $this->assertSame('192.168.1.1', $jsonData['consumer']->ip);
        $this->assertSame('session123', $jsonData['consumer']->sessionId);

        $customStore = new Store('123', '456', Environment::sandbox());
        $this->assertFalse($customStore->getEnvironment()->isProduction());

        $defaultStore = new Store('789', '012');
        $this->assertTrue($defaultStore->getEnvironment()->isProduction());
    }
}
