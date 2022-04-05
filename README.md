# Nordigen PHP Library

This is official PHP client library for [Nordigen](https://nordigen.com/en).

For a full list of endpoints and arguments, see the [docs](https://nordigen.com/en/account_information_documenation/api-documention/overview/).

Before starting to use API you will need to create a new secret and get your `SECRET_ID` and `SECRET_KEY` from the [Nordigen's Open Banking Portal](https://ob.nordigen.com/user-secrets/).


## Requirements

* PHP >= 7.4

## Installation

Install library via composer:

```sh
composer require nordigen/nordigen-php
```

## Example application

Laravel example application can be found in `example` directory

## Quickstart

To use the library, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once('vendor/autoload.php');
```

```php
// Get secretId and secretKey from ob.nordigen.com portal and pass them to NordigenClient
$secretId  = "YOUR_SECRET_ID";
$secretKey = "YOUR_SECRET_KEY";

$client = new \Nordigen\NordigenPHP\API\NordigenClient($secretId, $secretKey);

// Generate new access token. Token is valid for 24 hours
// Token is automatically injected into every response
$token = $client->createAccessToken();

// Get access token
$accessToken = $client->getAccessToken();
// Get refresh token
$refreshToken = $client->getRefreshToken();

// Exchange refresh token for new access token
$newToken = $client->exchangeToken($accessToken);

// Get list of institutions by country. Country should be in ISO 3166 standard.
$institutions = $client->institution->getInstitutionsByCountry("LV");

// Institution id can be gathered from getInstitutions response.
// Example Revolut ID
$institutionId = "REVOLUT_REVOGB21";
$redirectUri = "https://nordigen.com"

// Initialize new bank connection session
$session = $client->initSession($institutionId, $redirectUr);

// Get link to authorize in the bank
// Authorize with your bank via this link, to gain access to account data
$link = $session["link"];
// requisition id is needed to get accountId in the next step
$requisitionId = $session["requisition_id"];
```

After successful authorization with a bank you can fetch your data (details, balances, transactions)

## Fetching account metadata, balances, details and transactions

```php
// Get account id after completed authorization with a bank
$requisitionData = $client->requisition->getRequisition($requisitionId);
// Get account id from the array of accounts
$accountId = $requisitionData["accounts"][0];

// Instantiate account object
$account = $client->account($accountId);

// Fetch account metadata
$metadata = $account->getAccountMetaData();
// Fetch account balances
$balances = $account->getAccountBalances();
// Fetch account details
$details = $account->getAccountDetails();
// Fetch account transactions
$transactions = $account->getAccountTransactions();

// Optional. You can filter transactions by specific date range
$transactions = $account->getAccountTransactions("2021-12-01", "2022-01-30");
```

## Support

For any inquiries please contact support at [support@nordigen.com](support@nordigen.com) or create an issue in the repository.
