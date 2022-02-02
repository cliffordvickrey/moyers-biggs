<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Io;

use CliffordVickrey\MoyersBiggs\Infrastructure\Io\FileInterface;

class MockFile implements FileInterface
{
    private MockIo $io;
    private string $filename;

    /**
     * @param MockIo $io
     * @param string $filename
     */
    public function __construct(MockIo $io, string $filename)
    {
        $this->io = $io;
        $this->filename = $filename;
    }

    /**
     * @inheritDoc
     */
    public function lock(): void
    {
    }

    /**
     * @inheritDoc
     */
    public function close(): void
    {
    }

    /**
     * @inheritDoc
     */
    public function read(): string
    {
        return $this->io->data[$this->filename] ?? '';
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
    }

    /**
     * @inheritDoc
     */
    public function unlock(): void
    {
    }

    /**
     * @inheritDoc
     */
    public function write(string $contents): void
    {
        $this->io->data[$this->filename] = $contents;
    }
}
