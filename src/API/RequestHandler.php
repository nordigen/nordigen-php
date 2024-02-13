<?php

namespace Nordigen\NordigenPHP\API;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Nordigen\NordigenPHP\Http\RequestHandlerTrait;

class RequestHandler
{
    use RequestHandlerTrait;

    public function __construct(string $baseUri, string $secretId, string $secretKey, ?ClientInterface $client)
    {
        $this->authentication = [$secretId, $secretKey];
        $this->baseUri = $baseUri;
        $this->httpClient = $this->setHttpClient($client);
    }

    /**
     * Set headers for HttpClient
     * @param ClientInterface $client
     *
     * @return Client
     */
    public function setHttpClient($client): Client
    {
        if($client !== NULL) {
            return $client;
        }

        return new Client([
            "base_uri" => $this->baseUri,
            "headers" => [
                "accept" => "application/json",
                "User-Agent" => "Nordigen-PHP-v2"
            ]
        ]);
    }

    /**
     * Get authentication.
     *
     * @return array
     */
    public function getAuthentication(): array
    {
        return $this->authentication;
    }


}
