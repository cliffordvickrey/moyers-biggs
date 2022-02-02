<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Io;

use function glob;
use function is_file;
use function unlink;

/**
 * @codeCoverageIgnore
 */
class Io implements IoInterface
{
    private bool $lockingDisabled = false;

    /**
     * @return void
     */
    public function disableLocking(): void
    {
        $this->lockingDisabled = true;
    }

    /**
     * @inheritDoc
     */
    public function exists(string $filename): bool
    {
        return is_file($filename);
    }

    /**
     * @inheritDoc
     */
    public function glob(string $pattern): array
    {
        return glob($pattern) ?: [];
    }

    /**
     * @inheritDoc
     */
    public function openFileForReading(string $filename): FileInterface
    {
        return new File($filename, false, $this->lockingDisabled);
    }

    /**
     * @inheritDoc
     */
    public function openFileForWriting(string $filename): FileInterface
    {
        return new File($filename, true, $this->lockingDisabled);
    }

    /**
     * @inheritDoc
     */
    public function unlink(string $filename): bool
    {
        /** @noinspection PhpCastIsUnnecessaryInspection */
        return (bool)@unlink($filename);
    }
}
