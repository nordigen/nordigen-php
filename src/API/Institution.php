<?php

namespace Nordigen\NordigenPHP\API;

use Nordigen\NordigenPHP\API\RequestHandler;

class Institution
{

    private RequestHandler $requestHandler;

    public function __construct(RequestHandler $requestHandler) {
        $this->requestHandler = $requestHandler;
    }

    /**
     * Retrieve a list of Institutions by country.
     * @param string $countryCode ISO 3166 two-character country code
     *
     * @return array
     */
    public function getInstitutionsByCountry(string $countryCode): array
    {
        $response = $this->requestHandler->get('institutions/', [
            'query' => [
                'country' => $countryCode
            ]
        ]);
        $json = json_decode($response->getBody()->getContents(), true);
        return $json;
    }

    /**
     * Retrieve information about a single Institution
     * @param string $institutionId
     * 
     * @return array
     */
    public function getInstitution(string $institutionId): array
    {
        $response = $this->requestHandler->get("institutions/{$institutionId}/");
        $json = json_decode($response->getBody()->getContents(), true);
        return $json;
    }

}
