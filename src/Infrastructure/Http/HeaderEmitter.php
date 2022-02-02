<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Http;

use function header;

/**
 * @codeCoverageIgnore
 */
class HeaderEmitter implements HeaderEmitterInterface
{
    /**
     * @param string $header
     * @param bool $replace
     * @param int $status
     * @return void
     */
    public function emitHeader(string $header, bool $replace = true, int $status = 0): void
    {
        header($header, $replace, $status);
    }
}
