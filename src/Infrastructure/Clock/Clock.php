<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Clock;

use DateTimeImmutable;

final class Clock implements ClockInterface
{
    private ?DateTimeImmutable $now;

    /**
     * @param DateTimeImmutable|null $now
     */
    public function __construct(?DateTimeImmutable $now = null)
    {
        $this->now = $now;
    }

    /**
     * @inheritDoc
     */
    public function now(): DateTimeImmutable
    {
        return $this->now ?? new DateTimeImmutable();
    }
}
