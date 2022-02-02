<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Io;

use CliffordVickrey\MoyersBiggs\Infrastructure\Io\FileInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Io\IoInterface;

use function array_filter;
use function array_keys;
use function array_pad;
use function array_pop;
use function array_shift;
use function array_values;
use function explode;
use function implode;
use function str_starts_with;

class MockIo implements IoInterface
{
    /** @var array<string, string> */
    public array $data = [];

    /**
     * @inheritDoc
     */
    public function exists(string $filename): bool
    {
        return isset($this->data[self::normalizeFilename($filename)]);
    }

    /**
     * @param string $filename
     * @return string
     */
    private static function normalizeFilename(string $filename): string
    {
        $parts = array_pad(explode('/', $filename), 2, '');
        $basename = array_pop($parts);
        $dir = array_pop($parts);
        return implode('/', [$dir, $basename]);
    }

    /**
     * @inheritDoc
     */
    public function openFileForReading(string $filename): FileInterface
    {
        return new MockFile($this, self::normalizeFilename($filename));
    }

    /**
     * @inheritDoc
     */
    public function openFileForWriting(string $filename): FileInterface
    {
        return new MockFile($this, self::normalizeFilename($filename));
    }

    /**
     * @inheritDoc
     */
    public function glob(string $pattern): array
    {
        $filename = self::normalizeFilename($pattern);
        $parts = explode('/', $filename);
        $needle = array_shift($parts);

        $filenames = array_keys($this->data);

        return array_values(array_filter(
            $filenames,
            fn(string $filename): bool => str_starts_with($filename, $needle)
        ));
    }

    /**
     * @inheritDoc
     */
    public function unlink(string $filename): bool
    {
        $filename = self::normalizeFilename($filename);
        $deleted = isset($this->data[$filename]);
        unset($this->data[$filename]);
        return $deleted;
    }
}
