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

    public function get(string $uri, array $options = []): ResponseInterface
    {
        try {
            $response = $this->httpClient->get($uri, $options);
            return $response;
        } catch (BadResponseException $exc) {
            ExceptionHandler::handleException($exc->getResponse());
        }
    }

    public function post(string $uri, array $options = []): ResponseInterface
    {
        try {
            $response = $this->httpClient->post($uri, $options);
            return $response;
        } catch (BadResponseException $exc) {
            ExceptionHandler::handleException($exc->getResponse());
        }
    }

    public function put(string $uri, array $options = []): ResponseInterface
    {
        try {
            $response = $this->httpClient->put($uri, $options);
            return $response;
        } catch (BadResponseException $exc) {
            ExceptionHandler::handleException($exc->getResponse());
        }
    }

    public function delete(string $uri, array $options = []): ResponseInterface
    {
        try {
            $response = $this->httpClient->delete($uri, $options);
            return $response;
        } catch (BadResponseException $exc) {
            ExceptionHandler::handleException($exc->getResponse());
        }
    }
}
