<?php

namespace Nordigen\NordigenPHP\API;

use Nordigen\NordigenPHP\API\RequestHandler;
use Nordigen\NordigenPHP\Exceptions\BaseException;

class Account {

    private RequestHandler $requestHandler;
    private string $accountId;
    private int $rateLimit;
    private int $rateLimitRemaining;

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
        $response = $this->requestHandler->get("accounts/{$this->accountId}/");
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
        $response = $this->requestHandler->get("accounts/{$this->accountId}/balances/");
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
        $response = $this->requestHandler->get("accounts/{$this->accountId}/details/");
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

        try {
            $response = $this->requestHandler->get("accounts/{$this->accountId}/transactions/", $params);
            $json = json_decode($response->getBody()->getContents(), true);
            $exp = null;
        } catch (BaseException $exception) {
            $response = $exception->getResponse();
            $exp = $exception;
        }

        $this->rateLimit = $response->getHeader("http_x_ratelimit_account_success_limit")[0] ?? 0;
        $this->rateLimitRemaining = $response->getHeader("http_x_ratelimit_account_success_remaining")[0] ?? 0;

        if ($exp !== null) {
            throw $exp;
        }

        return $json;
    }

    /**
     * Retrieve premium account transactions.
     * @param ?string $country
     * @param ?string $dateFrom
     * @param ?string $dateTo
     *
     * @return array
     */
    public function getPremiumAccountTransactions(?string $country = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $params = [
            'query' => []
        ];

        if ($country)  $params['query']['country']   = $country;
        if ($dateFrom) $params['query']['date_from'] = $dateFrom;
        if ($dateTo)   $params['query']['date_to']   = $dateTo;

        $response = $this->requestHandler->get("accounts/premium/{$this->accountId}/transactions/", $params);
        $json = json_decode($response->getBody()->getContents(), true);
        return $json;
    }

    /**
     * @return int|null
     */
    public function getRateLimit(): int
    {
        return $this->rateLimit;
    }

    /**
     * @return int|null
     */
    public function getRateLimitRemaining(): int
    {
        return $this->rateLimitRemaining;
    }
}
