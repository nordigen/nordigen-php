<?php

namespace Nordigen\NordigenPHP\API;

use Nordigen\NordigenPHP\API\RequestHandler;

class EndUserAgreement
{
    private RequestHandler $requestHandler;

    public function __construct(RequestHandler $requestHandler) {
        $this->requestHandler = $requestHandler;
    }

    /**
     * Retrieve all End-user Agreements for a specific End-user.
     * @param string $endUserId The ID of the End-user in your system.
     *
     * @return array
     */
    public function getEndUserAgreements(): array
    {
        $response = $this->requestHandler->get('agreements/enduser/');
        $json = json_decode($response->getBody()->getContents(), true);
        return $json;
    }

    /**
     * Create a new End-user Agreement.
     * @param string $institutionId The ID of the Institution.
     * @param string[] $accessScope The requested access scope. All by default. See Enums\AccessScope for possible values.
     * @param int|null $maxHistoricalDays Maximum number of days of transaction data to retrieve. 90 by default.
     * @param int|null $accessValidForDays How long access to the end-user's account will be available. 90 days by default.
     *
     * @return array
     */
    public function createEndUserAgreement(
        string $institutionId,
        array $accessScope = ['details', 'balances', 'transactions'],
        int $maxHistoricalDays = 90,
        int $accessValidForDays = 90
    ): array
    {
        $payload = [
            'max_historical_days' => $maxHistoricalDays,
            'access_valid_for_days' => $accessValidForDays,
            'access_scope' => $accessScope,
            'institution_id' => $institutionId
        ];
        $response = $this->requestHandler->post('agreements/enduser/', [
            'json' => $payload
        ]);
        $json = json_decode($response->getBody()->getContents(), true);
        return $json;
    }

    /**
     * Retrieve an End-user Agreement.
     * @param string $euaId The ID of the End-user Agreement.
     *
     * @return array
     */
    public function getEndUserAgreement(string $endUserAgreementId): array
    {
        $response = $this->requestHandler->get("agreements/enduser/{$endUserAgreementId}/");
        $json = json_decode($response->getBody()->getContents(), true);
        return $json;
    }

    /**
     * Delete an End-user agreement.
     * @param string $euaId The ID of the End-user agreement.
     *
     * @return void
     */
    public function deleteEndUserAgreement(string $endUserAgreementId): void
    {
        $this->requestHandler->delete("agreements/enduser/{$endUserAgreementId}/");
    }

    /**
     * Accept an End-user agreement.
     * @param string $euaId The ID of the End-user Agreement.
     * @param string $userAgent The End-user's user agent.
     * @param string $ipAddress The End-user's IP address.
     *
     * @return array The newly accepted End-user agreement.
     */
    public function acceptEndUserAgreement(
        string $endUserAgreementId,
        string $userAgent,
        string $ipAddress
    ): array
    {
        $payload = [
            'user_agent' => $userAgent,
            'ip_address' => $ipAddress
        ];

        $response = $this->requestHandler->put("agreements/enduser/{$endUserAgreementId}/accept/", [
            'json' => $payload
        ]);
        $json = json_decode($response->getBody()->getContents(), true);
        return $json;
    }
}
