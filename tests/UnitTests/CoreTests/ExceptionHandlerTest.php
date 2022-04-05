<?php

namespace UnitTests\CoreTests;

use Nordigen\NordigenPHP\Exceptions\InstitutionExceptions\RateLimitError;
use Nordigen\NordigenPHP\Exceptions\ExceptionHandler;
use Nordigen\NordigenPHP\Exceptions\NordigenExceptions\NordigenException;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Response;

class ExceptionHandlerTest extends TestCase
{
    /**
     * @covers \Nordigen\NordigenPHP\Exceptions\ExceptionHandler
     */
    public function testCorrectExceptionIsThrown()
    {
        $jsonBody = json_encode([
            'detail' => 'Rate limit exceeded',
            'type' => 'RateLimitError'
        ]);
        $response = new Response(429, [], $jsonBody);

        $this->expectException(RateLimitError::class);
        ExceptionHandler::handleException($response);
    }

    /**
     * @covers \Nordigen\NordigenPHP\Exceptions\ExceptionHandler
     */
    public function testNordigenExceptionIsThrownWhenNoMatch()
    {
        $jsonBody = json_encode([
            'detail' => 'Rate limit exceeded',
            'type' => 'SomeNewError'
        ]);
        $response = new Response(401, [], $jsonBody);

        $this->expectException(NordigenException::class);
        ExceptionHandler::handleException($response);
    }
}
