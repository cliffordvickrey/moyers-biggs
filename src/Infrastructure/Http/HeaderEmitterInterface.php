<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Http;

interface HeaderEmitterInterface
{
    /**
     * @param string $header
     * @param bool $replace
     * @param int $status
     * @return void
     */
    public function emitHeader(string $header, bool $replace = true, int $status = 0): void;
}
