<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Io;

interface FileInterface
{
    /**
     * Locks the resource
     * @return void
     */
    public function lock(): void;

    /**
     * Closes the resource
     * @return void
     */
    public function close(): void;

    /**
     * Reads from a resource
     * @return string
     */
    public function read(): string;

    /**
     * Seeks back to the beginning of the resource
     * @return void
     */
    public function rewind(): void;

    /**
     * Unlocks the resource
     * @return void
     */
    public function unlock(): void;

    /**
     * Writes to the resource
     * @param string $contents
     * @return void
     */
    public function write(string $contents): void;
}
