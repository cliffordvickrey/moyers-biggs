<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\App\Error;

use CliffordVickrey\MoyersBiggs\App\Error\ErrorHandler;
use ErrorException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\App\Error\ErrorHandler
 */
class ErrorHandlerTest extends TestCase
{
    /**
     * @return void
     * @throws ErrorException
     */
    public function testHandle(): void
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionCode(0);
        ErrorHandler::handle(1, 'there was a PHP error', 'index.php', 100);
    }
}
