<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Infrastructure\Clock;

use CliffordVickrey\MoyersBiggs\Infrastructure\Clock\Clock;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Infrastructure\Clock\Clock
 */
class ClockTest extends TestCase
{
    /**
     * @return void
     */
    public function testNow(): void
    {
        $clock = new Clock();
        self::assertInstanceOf(DateTimeImmutable::class, $clock->now());
    }
}
