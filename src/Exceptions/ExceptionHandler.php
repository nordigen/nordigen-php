<?php

namespace Nordigen\NordigenPHP\Exceptions;

use Nordigen\NordigenPHP\Exceptions\InstitutionExceptions;
use Nordigen\NordigenPHP\Exceptions\NordigenExceptions\NordigenException;
use Psr\Http\Message\ResponseInterface;

class ExceptionHandler
{
    private static array $institutionExceptionMap = [
        'UnknownRequestError' => InstitutionExceptions\UnknownRequestError::class,
        'AccessExpiredError' => InstitutionExceptions\AccessExpiredError::class,
        'AccountInactiveError' => InstitutionExceptions\AccountInactiveError::class,
        'ConnectionError' => InstitutionExceptions\InstitutionConnectionError::class,
        'ServiceError' => InstitutionExceptions\InstitutionServiceError::class,
        'RateLimitError' => InstitutionExceptions\RateLimitError::class,
    ];

    /**
     * Get exception type
     *
     * @param array $response
     * @return void
     */
    private static function getExceptionType(array $response)
    {
        $errorType = $response['type'] ?? 'NordigenException';
        return $errorType;
    }

    /**
     * Handle Exception
     *
     * @param ResponseInterface $response
     * @return void
     */
    public static function handleException(ResponseInterface $response): void
    {
        $content = $response->getBody()->getContents();
        $json = json_decode($content, true);
        $errorType = self::getExceptionType($json);
        $summary = $json['summary'] ?? '';
        $detail = $json['detail'] ?? '';
        $message = "{$summary} {$detail}";
        $errorCode = $response->getStatusCode();

        $exception = self::$institutionExceptionMap[$errorType] ?? NordigenException::class;
        if ($exception == NordigenException::class) {
            $message = $summary;
        }

        throw new $exception($response, $message, $errorCode);
    }
}
