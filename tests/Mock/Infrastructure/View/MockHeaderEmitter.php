<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\View;

use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HeaderEmitterInterface;

use function array_map;
use function array_pad;
use function explode;

class MockHeaderEmitter implements HeaderEmitterInterface
{
    /** @var array<string, list<string>> */
    public array $headers = [];
    /** @var int<100, 599> */
    public int $status = 200;

    /**
     * @inheritDoc
     */
    public function emitHeader(string $header, bool $replace = true, int $status = 0): void
    {
        if ($status >= 100 && $status <= 599) {
            $this->status = $status;
        }

        $parts = array_map('\\trim', array_pad(explode(':', $header, 2), 2, ''));
        list ($name, $value) = $parts;

        if ($replace || !isset($this->headers[$name])) {
            $this->headers[$name] = [$value];
            return;
        }

        $this->headers[$name][] = $value;
    }
}
