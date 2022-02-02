<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Clock;

use DateTimeImmutable;

interface ClockInterface
{
    /**
     * @return DateTimeImmutable
     */
    public function now(): DateTimeImmutable;
}
