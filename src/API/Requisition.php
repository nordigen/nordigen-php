<?php

namespace Nordigen\NordigenPHP\API;

use Nordigen\NordigenPHP\API\RequestHandler;

class Requisition
{

    private RequestHandler $requestHandler;

    public function __construct(RequestHandler $requestHandler) {
        $this->requestHandler = $requestHandler;
    }

    /**
     * Get all requisitions associated with your company.
     *
     * @return array
     */
    public function getRequisitions(): array
    {
        $response = $this->requestHandler->get('requisitions/');
        $json = json_decode($response->getBody()->getContents(), true);
        return $json;
    }

    /**
     * Create a new requisition.
     * @param string $redirect The URI where the End-user will be redirected to after authentication.
     * @param string $institutionId The ID of the institution.
     * @param string|null $endUserAgreementId The ID of the EUA to associate with this requisition. A new one will be created if no ID is provided.
     * @param string|null $reference Additional ID to identify the End-user. This value will be appended to the redirect.
     * @param string|null $userLanguage Language to use in views. Two-letter country code (ISO 639-1).
     * @param string|null $ssn SSN (social security number) field to verify ownership of the account.
     * @param bool|null $accountSelection Option to enable account selection view for the end user.
     * @param bool|null $redirectImmediate Option to enable redirect back to the client after account list received
     *
     * @return array
     */
    public function createRequisition(
        string $redirect,
        string $institutionId,
        ?string $endUserAgreementId = null,
        ?string $reference = null,
        ?string $userLanguage = null,
        ?string $ssn = null,
        ?bool $accountSelection = null,
        ?bool $redirectImmediate = null
    ): array
    {
        $payload = [
            'redirect' => $redirect,
            'institution_id' => $institutionId
        ];
        if ($endUserAgreementId) $payload['agreement'] = $endUserAgreementId;
        if ($reference)          $payload['reference'] = $reference;
        if ($userLanguage)       $payload['user_language'] = $userLanguage;
        if ($ssn)                $payload['ssn'] = $ssn;
        if ($accountSelection)   $payload['account_selection'] = $accountSelection;
        if ($redirectImmediate)   $payload['redirect_immediate'] = $redirectImmediate;

        $response = $this->requestHandler->post('requisitions/', [
            'json' => $payload
        ]);
        $json = json_decode($response->getBody()->getContents(), true);
        return $json;
    }

    /**
     * Retrieve a requisition by its ID.
     * @param string $requisitionId The ID of the requisition.
     *
     * @return array
     */
    public function getRequisition(string $requisitionId): array
    {
        $response = $this->requestHandler->get("requisitions/{$requisitionId}/");
        $json = json_decode($response->getBody()->getContents(), true);
        return $json;
    }

    /**
     * Delete a requisition by its ID.
     * @param string $requisitionId The ID of the requisition.
     *
     * @return bool Whether the requisition was successfully deleted.
     */
    public function deleteRequisition(string $requisitionId): void
    {
        $this->requestHandler->delete("requisitions/{$requisitionId}/");
    }

}
