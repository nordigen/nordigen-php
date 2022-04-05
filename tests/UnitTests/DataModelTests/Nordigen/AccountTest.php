<?php

namespace UnitTests\DataModelTests\Nordigen;

use DateTime;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Nordigen\NordigenPHP\API\Account;
use Nordigen\NordigenPHP\API\NordigenClient;

class AccountTest extends TestCase
{
    private static ?Client $client;
    private static NordigenClient $nordigenClient;
    private static ?MockHandler $mock;
    private static ?HandlerStack $handlerStack;

    private static string $responseRequisition;
    private static string $accountId;
    private static Account $accountInstance;

    public static function setUpBeforeClass(): void
    {
        self::$mock = new MockHandler();
        self::$handlerStack = HandlerStack::create(self::$mock);
        self::$client = new Client(['handler' => self::$handlerStack]);
        self::$nordigenClient = new NordigenClient(
            'SECRET_ID',
            'SECRET_KEY',
            self::$client
        );

        self::$responseRequisition = '
        {
            "id": "3fa85f64-5717-4562-b3fc-2c963f66afa6",
            "created": "2021-10-18T10:02:17.928Z",
            "redirect": "https://example.com",
            "status": "CR",
            "agreement": "3fa85f64-5717-4562-b3fc-2c963f66afa6",
            "accounts": [
                "3fa85f64-5717-4562-b3fc-2c963f66afa6"
            ],
            "reference": "1234",
            "institution_id": "REVOLUT",
            "link": "https://nordigen.com",
            "enduser_id": "1234",
            "user_language": "LV"
        }';
        self::$accountId = json_decode(self::$responseRequisition, true)["accounts"][0];
        self::$accountInstance = self::$nordigenClient->account(self::$accountId);
    }

    public static function tearDownAfterClass(): void
    {
        self::$mock = null;
        self::$client = null;
        self::$handlerStack = null;
    }

    /**
     * @covers Nordigen\NordigenPHP\API\Account
     */
    public function testAccountData()
    {
        $responseBody = '
            {
                "id": "3fa85f64-5717-4562-b3fc-2c963f66afa6",
                "created": "2022-02-22T10:37:34.556Z",
                "last_accessed": "2022-02-22T10:37:34.556Z",
                "iban": "1234",
                "institution_id": "identifier",
                "status": "DISCOVERED"
            }
        ';

        $response = new Response(200, [], $responseBody);
        self::$mock->append($response);
        $result = self::$accountInstance->getAccountMetaData();
        $this->assertEquals(json_decode($responseBody, true), $result);
    }

    /**
     * @covers Nordigen\NordigenPHP\API\Account
     */
    public function testAccountDetails()
    {
        $responseBody = '
            {
                "account": {
                    "resourceId": "534252452",
                    "iban": "IBAN",
                    "currency": "EUR"
                }
            }
        ';

        $response = new Response(200, [], $responseBody);
        self::$mock->append($response);
        $result = self::$accountInstance->getAccountDetails();
        $this->assertEquals(json_decode($responseBody, true), $result);
    }


    /**
     * @covers Nordigen\NordigenPHP\API\Account
     */
    public function testAccountBalances()
    {
        $responseBody = '
            {
                "balances": [
                    {
                        "balanceAmount": {
                            "amount": "657.49",
                            "currency": "EUR"
                        },
                        "balanceType": "EUR"
                    }
                ]
            }
        ';

        $response = new Response(200, [], $responseBody);
        self::$mock->append($response);
        $result = self::$accountInstance->getAccountBalances();
        $this->assertEquals(json_decode($responseBody, true), $result);
    }


    /**
     * @covers Nordigen\NordigenPHP\API\Account
     */
    public function testAccountTransactions()
    {
        $responseBody = '
            {
                "transactions": {
                    "booked": [
                        {
                            "trxAmount": {
                                "currency": "EUR",
                                "amount": "328.18"
                            }
                        }
                    ]
                }
            }
        ';

        $response = new Response(200, [], $responseBody);
        self::$mock->append($response);
        $result = self::$accountInstance->getAccountTransactions();
        $this->assertEquals(json_decode($responseBody, true), $result);
    }

}
