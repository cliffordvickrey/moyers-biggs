<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\App\Error;

use ErrorException;

use function error_reporting;

/**
 * Raise notices, warnings, and errors to exceptions
 */
final class ErrorHandler
{
    /**
     * @param int $severity
     * @param string $message
     * @param string|null $file
     * @param int|null $line
     * @return void
     * @throws ErrorException
     */
    public static function handle(int $severity, string $message, ?string $file, ?int $line): void
    {
        // @codeCoverageIgnoreStart
        if (!(error_reporting() & $severity)) {
            return;
        }
        // @codeCoverageIgnoreEnd

        throw new ErrorException($message, 0, $severity, $file, $line);
    }
}
