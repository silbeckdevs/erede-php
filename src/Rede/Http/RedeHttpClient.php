<?php

namespace Rede\Http;

use Psr\Log\LoggerInterface;
use Rede\eRede;
use Rede\Store;

abstract class RedeHttpClient
{
    public const GET = 'GET';

    public const POST = 'POST';

    public const PUT = 'PUT';

    public const CONTENT_TYPE_JSON = 'application/json; charset=utf8';

    public const CONTENT_TYPE_FORM_URLENCODED = 'application/x-www-form-urlencoded';

    private ?string $platform = null;

    private ?string $platformVersion = null;

    public function __construct(protected Store $store, protected ?LoggerInterface $logger = null)
    {
    }

    public function platform(?string $platform, ?string $platformVersion): static
    {
        $this->platform = $platform;
        $this->platformVersion = $platformVersion;

        return $this;
    }

    /**
     * @param string|array<string|int,mixed>|object $body
     * @param array<string|int, string>             $headers
     *
     * @throws \RuntimeException
     */
    protected function request(
        string $method,
        string $url,
        string|array|object $body = '',
        array $headers = [],
        string $contentType = self::CONTENT_TYPE_JSON,
    ): RedeResponse {
        $curl = curl_init($url);

        if (!$curl instanceof \CurlHandle) {
            throw new \RuntimeException('Was not possible to create a curl instance.');
        }

        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

        switch ($method) {
            case self::GET:
                break;
            case self::POST:
                curl_setopt($curl, CURLOPT_POST, true);
                break;
            default:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        }

        $requestHeaders = [
            str_replace('  ', ' ', $this->getUserAgent()),
            'Accept: application/json',
            'Transaction-Response: brand-return-opened',
        ];

        if ($this->store->getOAuthToken()) {
            $requestHeaders[] = 'Authorization: Bearer ' . $this->store->getOAuthToken()->getAccessToken();
        }

        $parsedBody = $this->parseBody($body, $contentType);
        if (!empty($body)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $parsedBody);

            $requestHeaders[] = "Content-Type: $contentType";
        } else {
            $requestHeaders[] = 'Content-Length: 0';
        }

        $customHeaders = [];
        if (!empty($headers)) {
            foreach ($headers as $key => $value) {
                $newHeader = is_numeric($key) ? trim($value) : trim($key) . ': ' . trim($value);
                if ($newHeader) {
                    $customHeaders[] = $newHeader;
                }
            }
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, array_merge($customHeaders, $requestHeaders));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $this->logger?->debug(
            trim(
                sprintf(
                    "Request Rede\n%s %s\n%s\n\n%s",
                    $method,
                    $url,
                    implode("\n", $headers),
                    preg_replace('/"(cardHolderName|cardnumber|securitycode)":"[^"]+"/i', '"\1":"***"', $parsedBody)
                )
            )
        );

        $response = curl_exec($curl);
        $httpInfo = curl_getinfo($curl);

        $this->logger?->debug(
            sprintf(
                "Response Rede\nStatus Code: %s\n\n%s",
                $httpInfo['http_code'],
                $response
            )
        );

        $this->dumpHttpInfo($httpInfo);

        if (curl_errno($curl)) {
            throw new \RuntimeException(sprintf('Curl error[%s]: %s', curl_errno($curl), curl_error($curl)));
        }

        if (!is_string($response)) {
            throw new \RuntimeException('Error obtaining a response from the API');
        }

        curl_close($curl);

        return new RedeResponse($httpInfo['http_code'], $response);
    }

    private function parseBody(mixed $body, string $contentType): string
    {
        if (empty($body)) {
            return '';
        }

        if (is_string($body)) {
            return $body;
        }

        if (self::CONTENT_TYPE_FORM_URLENCODED === $contentType) {
            return http_build_query($body);
        }

        return json_encode($body) ?: '';
    }

    private function getUserAgent(): string
    {
        $userAgent = sprintf(
            'User-Agent: %s',
            sprintf(
                eRede::USER_AGENT,
                phpversion(),
                $this->store->getFiliation(),
                php_uname('s'),
                php_uname('r'),
                php_uname('m')
            )
        );

        if (!empty($this->platform) && !empty($this->platformVersion)) {
            $userAgent .= sprintf(' %s/%s', $this->platform, $this->platformVersion);
        }

        $curlVersion = curl_version();

        if (is_array($curlVersion)) {
            $userAgent .= sprintf(
                ' curl/%s %s',
                $curlVersion['version'] ?? '',
                $curlVersion['ssl_version'] ?? ''
            );
        }

        return $userAgent;
    }

    /**
     * @param array<string, mixed> $httpInfo
     */
    private function dumpHttpInfo(array $httpInfo): void
    {
        foreach ($httpInfo as $key => $info) {
            if (is_array($info)) {
                foreach ($info as $infoKey => $infoValue) {
                    $this->logger?->debug(sprintf('Curl[%s][%s]: %s', $key, $infoKey, implode(',', $infoValue)));
                }

                continue;
            }

            $this->logger?->debug(sprintf('Curl[%s]: %s', $key, $info));
        }
    }
}
