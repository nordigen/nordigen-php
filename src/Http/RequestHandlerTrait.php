<?php

namespace Nordigen\NordigenPHP\Http;

use GuzzleHttp\Exception\BadResponseException;
use Nordigen\NordigenPHP\Exceptions\ExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\ClientInterface;

trait RequestHandlerTrait
{
    protected ClientInterface $httpClient;
    protected string $baseUri;

    protected ?string $accessToken = null;

    private function mergeBearerToken(array $options): array {
        if ($this->accessToken !== null) {
            return array_merge(
                $options,
                [
                    'headers' => array_merge(
                        ["Authorization" => "Bearer {$this->accessToken}"],
                        $options['headers'] ?? [],
                    ),
                ],
            );
        }
        return $options;
    }

    public function get(string $uri, array $options = []): ResponseInterface
    {
        try {
            $options = $this->mergeBearerToken($options);
            $response = $this->httpClient->get($uri, $options);
            return $response;
        } catch (BadResponseException $exc) {
            ExceptionHandler::handleException($exc->getResponse());
        }
    }

    public function post(string $uri, array $options = []): ResponseInterface
    {
        try {
            $options = $this->mergeBearerToken($options);
            $response = $this->httpClient->post($uri, $options);
            return $response;
        } catch (BadResponseException $exc) {
            ExceptionHandler::handleException($exc->getResponse());
        }
    }

    public function put(string $uri, array $options = []): ResponseInterface
    {
        try {
            $options = $this->mergeBearerToken($options);
            $response = $this->httpClient->put($uri, $options);
            return $response;
        } catch (BadResponseException $exc) {
            ExceptionHandler::handleException($exc->getResponse());
        }
    }

    public function delete(string $uri, array $options = []): ResponseInterface
    {
        try {
            $options = $this->mergeBearerToken($options);
            $response = $this->httpClient->delete($uri, $options);
            return $response;
        } catch (BadResponseException $exc) {
            ExceptionHandler::handleException($exc->getResponse());
        }
    }

    /**
     * Get access token
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * Set existing access token.
     * @param string $accessToken
     *
     * @return void
     */
    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }
}
