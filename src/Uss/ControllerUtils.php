<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Uss;

use Throwable;

class ControllerUtils
{
    public const MESSAGE_SERVER_ERROR = 'try again later';

    /** The errors that are the client's fault, with their HTTP status */
    private const CLIENT_ERRORS = [
        ValidationError::class => 400,
        AuthenticationError::class => 401,
        AccountNotVerifiedError::class => 400,
    ];

    public static function calcHttpErrorCode(Throwable $e): int
    {
        return self::CLIENT_ERRORS[$e::class] ?? 500;
    }

    /**
     * Clients get the details of their own errors only, never the server's internals.
     */
    public static function calcErrorMessage(Throwable $e): string
    {
        return self::calcHttpErrorCode($e) < 500 ? $e->getMessage() : self::MESSAGE_SERVER_ERROR;
    }

    /**
     * Runs a service call and maps its outcome to the HTTP result:
     * the result with the given status, or an error status with a message.
     *
     * @param callable(): mixed $serviceCall
     */
    public static function respond(int $successStatus, callable $serviceCall): HttpResult
    {
        try {
            return new HttpResult($successStatus, $serviceCall());
        } catch (Throwable $serviceError) {
            $status = self::calcHttpErrorCode($serviceError);
            if ($status >= 500) {
                error_log((string) $serviceError);
            }

            return new HttpResult($status, ['error' => self::calcErrorMessage($serviceError)]);
        }
    }
}
