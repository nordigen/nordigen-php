<?php

namespace Nordigen\NordigenPHP\API;

use Nordigen\NordigenPHP\API\RequestHandler;

class PremiumAccount {

    private RequestHandler $requestHandler;

    public function __construct(RequestHandler $requestHandler, string $accountId) {
        $this->requestHandler = $requestHandler;
        $this->accountId = $accountId;
    }

    /**
     * Retrieve account meta-data.
     * @param string $accountId
     *
     * @return array
     */
    public function getAccountMetaData(): array
    {
        $response = $this->requestHandler->get("accounts/premium/{$this->accountId}/");
        $json = json_decode($response->getBody()->getContents(), true);
        return $json;
    }

    /**
     * Retrieve account balances.
     * @param string $accountId
     *
     * @return array
     */
    public function getAccountBalances(): array
    {
        $response = $this->requestHandler->get("accounts/premium/{$this->accountId}/balances/");
        $json = json_decode($response->getBody()->getContents(), true);
        return $json;
    }

    /**
     * Retrieve account details.
     * @param string $accountId
     *
     * @return array
     */
    public function getAccountDetails(): array
    {
        $response = $this->requestHandler->get("accounts/premium/{$this->accountId}/details/");
        $json = json_decode($response->getBody()->getContents(), true);
        return $json;
    }

    /**
     * Retrieve account transactions.
     * @param string $accountId
     *
     * @return array
     */
    public function getAccountTransactions(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $params = [
            'query' => []
        ];

        if($dateFrom) $params['query']['date_from'] = $dateFrom;
        if($dateTo)   $params['query']['date_to']   = $dateTo;

        $response = $this->requestHandler->get("accounts/premium/{$this->accountId}/transactions/", $params);
        $json = json_decode($response->getBody()->getContents(), true);
        return $json;
    }

}
