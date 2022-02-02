<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Io;

/**
 * File I/O methods
 */
interface IoInterface
{
    /**
     * Does the file exist?
     * @param string $filename
     * @return bool
     */
    public function exists(string $filename): bool;

    /**
     * Opens a file that already exists for reading
     * @param string $filename
     * @return FileInterface
     */
    public function openFileForReading(string $filename): FileInterface;

    /**
     * Opens a file for writing/appending. File will not be truncated automatically
     * @param string $filename
     * @return FileInterface
     */
    public function openFileForWriting(string $filename): FileInterface;

    /**
     * Fetches files/folders in a directory
     * @param string $pattern
     * @return list<string>
     */
    public function glob(string $pattern): array;

    /**
     * Deletes a file
     * @param string $filename
     * @return bool
     */
    public function unlink(string $filename): bool;
}
