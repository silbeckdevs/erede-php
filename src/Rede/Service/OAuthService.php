<?php

namespace Rede\Service;

use Rede\AccessToken;
use Rede\Exception\RedeException;
use Rede\Http\RedeHttpClient;

class OAuthService extends RedeHttpClient
{
    /**
     * Gera um novo access token via OAuth 2.0.
     *
     * @throws RedeException
     * @throws \RuntimeException
     */
    public function generateAccessToken(): AccessToken
    {
        $this->store->setAccessToken(null);

        $headers = [
            'Authorization: Basic ' . base64_encode($this->store->getFiliation() . ':' . $this->store->getToken()),
        ];

        $httpResponse = $this->request(
            method: self::POST,
            url: $this->store->getEnvironment()->getBaseUrlOAuth() . '/oauth2/token',
            body: ['grant_type' => 'client_credentials'],
            headers: $headers,
            contentType: self::CONTENT_TYPE_FORM_URLENCODED,
        );

        if (!$httpResponse->isSuccess()) {
            throw new RedeException('Failed to generate access token', $httpResponse->getStatusCode());
        }

        $accessToken = (new AccessToken())->populate(json_decode($httpResponse->getBody()));
        // -60 para garantir que o token ainda está válido
        $accessToken->setExpiresAt(time() + $accessToken->getExpiresIn() - 60);

        return $accessToken;
    }
}
