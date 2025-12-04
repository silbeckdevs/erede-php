<?php

namespace Rede\Service;

use Rede\Exception\RedeException;
use Rede\Http\RedeHttpClient;
use Rede\OAuthToken;

class OAuthService extends RedeHttpClient
{
    /**
     * Gera um novo access token via OAuth 2.0.
     *
     * @throws RedeException
     * @throws \RuntimeException
     */
    public function generateToken(): OAuthToken
    {
        // prevent send auth token in request header
        $this->store->setOAuthToken(null);

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

        $oauthToken = (new OAuthToken())->populate(json_decode($httpResponse->getBody()));
        // -60 para garantir que o token ainda está válido
        $oauthToken->setExpiresAt(time() + $oauthToken->getExpiresIn() - 60);

        return $oauthToken;
    }
}
