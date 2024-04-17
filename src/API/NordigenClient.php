<?php

namespace Nordigen\NordigenPHP\API;

use Nordigen\NordigenPHP\API\Institution;
use Nordigen\NordigenPHP\API\RequestHandler;
use GuzzleHttp\ClientInterface;
use Nordigen\NordigenPHP\Enums\AccessScope;

class NordigenClient
{
    public const BASE_URL = 'https://bankaccountdata.gocardless.com/api/v2/';

    private RequestHandler $requestHandler;
    public Institution $institution;
    public EndUserAgreement $endUserAgreement;
    public Requisition $requisition;

    private string $refreshToken;
    private string $requisitionLink;

    public function __construct(string $secretId, string $secretKey, ?ClientInterface $client = null)
    {
        $this->requestHandler   = new RequestHandler(self::BASE_URL, $secretId, $secretKey, $client);
        $this->institution      = new Institution($this->requestHandler);
        $this->endUserAgreement = new EndUserAgreement($this->requestHandler);
        $this->requisition      = new Requisition($this->requestHandler);
    }

    /**
     * @param string $accountId Account identifier.
     *
     * @return Account
     */
    public function account(string $accountId): Account
    {
        return new Account($this->requestHandler, $accountId);
    }


    /**
     * Perform all the necessary steps in order to retrieve the URL for user authentication. <br>
     * A new End-User agreement and requisition will be created.
     *
     * The result will be an array containing the URL for user authentication and the IDs of the
     * newly created requisition and End-user agreement.
     * @param string $institutionIdentifier ID of the Institution.
     * @param int $maxHistoricalDays Maximum number of days of transaction data to retrieve. 90 by default.
     * @param int $accessValidForDays How long access to the end-user's account will be available. 90 days by default.
     * @param string $endUserId The ID of the End-user in the client's system.
     * @param string $reference Additional ID to identify the End-user. This value will be appended to the redirect.
     * @param string $redirect The URI where the End-user will be redirected to after authentication.
     * @param AccessScope[] $accessScope The requested access scope. All by default. See Enums\AccessScope for possible values.
     * @param string|null $userLanguage Language to use in views. Two-letter country code (ISO 639-1).
     * @param string|null $ssn SSN (social security number) field to verify ownership of the account.
     * @param bool|null $accountSelection Option to enable account selection view for the end user.
     * @param bool|null $redirectImmediate Option to enable redirect back to the client after account list received
     *
     * @return array
     */
    public function initSession(
        string  $institutionIdentifier,
        string  $redirect,
        int     $maxHistoricalDays = 90,
        int     $accessValidForDays = 90,
        ?string $reference = null,
        ?array $accessScopes = ['details', 'balances', 'transactions'],
        ?string $userLanguage = null,
        ?string $ssn = null,
        ?bool $accountSelection = null,
        ?bool $redirectImmediate = null
    ): array
    {
        $endUserAgreement = $this->endUserAgreement->createEndUserAgreement(
            $institutionIdentifier,
            $accessScopes,
            $maxHistoricalDays,
            $accessValidForDays
        );
        $requisition = $this->requisition->createRequisition(
            $redirect,
            $institutionIdentifier,
            $endUserAgreement["id"],
            $reference,
            $userLanguage,
            $ssn,
            $accountSelection,
            $redirectImmediate
        );
        $result = [
            'link' => $requisition["link"],
            'requisition_id' => $requisition["id"],
            'agreement_id' => $endUserAgreement["id"]
        ];
        return $result;
    }


    /**
     * Create a new access token.
     *
     * @return array
     */
    public function createAccessToken(): array
    {
        [$secretId, $secretKey] = $this->requestHandler->getAuthentication();
        $response = $this->requestHandler->post('token/new/', [
            'headers' => [],
            'json' => [
                'secret_id' => $secretId,
                'secret_key' => $secretKey
            ]
        ]);
        $json = json_decode($response->getBody()->getContents(), true);
        $this->setAccessToken($json["access"]);
        $this->refreshToken = $json["refresh"];
        return $json;
    }


    /**
     * Refresh an access token.
     *
     * @param string $refreshToken
     * @return array
     */
    public function refreshAccessToken($refreshToken): array
    {
        $response = $this->requestHandler->post('token/refresh/', [
            'json' => [
                'refresh' => $refreshToken
            ]
        ]);
        $json = json_decode($response->getBody()->getContents(), true);
        $this->setAccessToken($json["access"]);
        return $json;
    }

    /**
     * Get the value of accessToken in the request handler.
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->requestHandler->getAccessToken();
    }

    /**
     * Set the value of accessToken in the request handler.
     *
     * @return  self
     */
    public function setAccessToken($accessToken): self
    {
        $this->requestHandler->setAccessToken($accessToken);
        return $this;
    }

    /**
     * Get the value of refreshToken
     *
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * Set the value of refreshToken
     *
     * @return  self
     */
    public function setRefreshToken($refreshToken): self
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    /**
     * Get the value of requisitionLink
     *
     * @return string
     */
    public function getRequisitionLink(): string
    {
        return $this->requisitionLink;
    }

    /**
     * Set the value of requisitionLink
     *
     * @return  self
     */
    public function setRequisitionLink($requisitionLink): self
    {
        $this->requisitionLink = $requisitionLink;

        return $this;
    }
}
