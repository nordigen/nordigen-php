<?php

namespace UnitTests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Nordigen\NordigenPHP\API\NordigenClient;
use PHPUnit\Framework\TestCase;

class NordigenIntegrationTest extends TestCase
{
    private static ?Client $client;
    private static NordigenClient $nordigenClient;
    private static ?MockHandler $mock;
    private static ?HandlerStack $handlerStack;
    private static string $responseEUA;
    private static string $responseRequisition;

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
        self::$responseEUA = '
        {
            "id": "1234",
            "created": "2021-01-01",
            "max_historical_days": 90,
            "access_valid_for_days": 90,
            "access_scope": [
                "balances",
                "details",
                "transactions"
            ],
            "institution_id": "BANK",
            "accepted": null
        }';
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
    }

    public static function tearDownAfterClass(): void
    {
        self::$mock = null;
        self::$client = null;
        self::$handlerStack = null;
    }

    /**
     * @covers \Nordigen\NordigenPHP\NordigenIntegration
     */
    public function testInstitutionIsCreatedFromResponse(): void
    {
        $responseBody = '
            {
                "id": "ABNAMRO_FTSBDEFAXXX",
                "name": "ABN AMRO Bank Commercial",
                "bic": "FTSBDEFAXXX",
                "transaction_total_days": "558",
                "countries": [
                    "DE"
                ],
                "logo": "https://cdn-logos.gocardless.com/ais/ABNAMRO_FTSBDEFAXXX.png"
            }';
        $response = new Response(200, [], $responseBody);
        self::$mock->append($response);
        $result = self::$nordigenClient->institution->getInstitution('ABNAMRO_FTSBDEFAXXX');
        $this->assertEquals(json_decode($responseBody, true), $result);
    }

    /**
     * @covers \Nordigen\NordigenPHP\API\Institution
     */
    public function testMultipleInstitutionObjectsAreCreatedFromResponse(): void
    {
        $responseBody = '
            [
                {
                    "id": "ABNAMRO_FTSBDEFAXXX",
                    "name": "ABN AMRO Bank Commercial",
                    "bic": "FTSBDEFAXXX",
                    "transaction_total_days": "558",
                    "countries": [
                        "DE"
                    ],
                    "logo": "https://cdn-logos.gocardless.com/ais/ABNAMRO_FTSBDEFAXXX.png"
                },
                {
                    "id": "SOMETHING",
                    "name": "SOMETHING",
                    "bic": "SOMETHING",
                    "transaction_total_days": "123",
                    "countries": [
                        "EE"
                    ],
                    "logo": "SOMETHING"
                }
            ]';
        $response = new Response(200, [], $responseBody);
        self::$mock->append($response);
        $result = self::$nordigenClient->institution->getInstitutionsByCountry('DE');
        $this->assertEquals(json_decode($responseBody, true), $result);
    }

    /**
     * @covers \Nordigen\NordigenPHP\API\EndUserAgreement
     */
    public function testEUAIsCreatedFromResponse()
    {
        $response = new Response(200, [], self::$responseEUA);
        self::$mock->append($response);
        $actual = self::$nordigenClient->endUserAgreement->getEndUserAgreement('1234');
        $this->assertEquals(json_decode(self::$responseEUA, true), $actual);
    }


    /**
     * @covers \Nordigen\NordigenPHP\API\EndUserAgreement
     */
    public function testCreateEua()
    {

        $response = new Response(200, [], self::$responseEUA);
        self::$mock->append($response);
        $result = self::$nordigenClient->endUserAgreement->createEndUserAgreement("TEST");
        $this->assertEquals(json_decode(self::$responseEUA, true), $result);
    }

    /**
     * @covers Nordigen\NordigenPHP\Nordigen\Requisition
     */
    public function testRequisitionIsCreatedFromResponse()
    {
        $response = new Response(200, [], self::$responseRequisition);
        self::$mock->append($response);
        $actual = self::$nordigenClient->requisition->getRequisition('3fa85f64-5717-4562-b3fc-2c963f66afa6');

        $this->assertEquals(json_decode(self::$responseRequisition, true), $actual);
    }

    /**
     * @covers Nordigen\NordigenPHP\DTO\Nordigen\NordigenClient
     */
    public function testInitSession()
    {

        $responseEUA = new Response(200, [], self::$responseEUA);
        self::$mock->append($responseEUA);

        $responseRequisition = new Response(200, [], self::$responseRequisition);
        self::$mock->append($responseRequisition);

        $responseBody = '
            {
                "link": "https://requisition-link.com",
                "requisitionId": "11111",
                "endUserAgreementId": "22222"
            }
        ';

        $response = new Response(200, [], $responseBody);
        self::$mock->append($response);
        $actual = self::$nordigenClient->initSession(
            'BANK',
            'https://nordigen.com',
            90
        );
        $expected = [
            "link" => "https://nordigen.com",
            "requisitionId" => "3fa85f64-5717-4562-b3fc-2c963f66afa6",
            "endUserAgreementId" => "1234"
        ];
        $this->assertEquals($expected, $actual);
    }
}
