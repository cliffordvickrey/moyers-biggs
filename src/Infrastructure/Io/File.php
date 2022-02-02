<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Io;

use CliffordVickrey\MoyersBiggs\Infrastructure\Exception\IoException;
use OutOfBoundsException;
use Throwable;

use function fclose;
use function flock;
use function fopen;
use function ftruncate;
use function fwrite;
use function is_resource;
use function rewind;
use function sprintf;
use function stream_get_contents;

use const LOCK_EX;
use const LOCK_SH;
use const LOCK_UN;

/**
 * @codeCoverageIgnore
 */
class File implements FileInterface
{
    private mixed $resource;
    private int $lockFlag;
    private bool $locked = false;

    /**
     * @param string $fileName
     * @param bool $forWriting
     * @param bool $lockingDisabled
     */
    public function __construct(string $fileName, bool $forWriting = false, bool $lockingDisabled = false)
    {
        $resource = fopen($fileName, $forWriting ? 'a+' : 'r+');

        if (!is_resource($resource)) {
            throw new IoException(sprintf('Could not open %s for %s', $fileName, $forWriting ? 'writing' : 'reading'));
        }

        $this->lockFlag = $lockingDisabled ? 0 : ($forWriting ? LOCK_EX : LOCK_SH);
        $this->resource = $resource;
    }

    /**
     *
     */
    public function __destruct()
    {
        try {
            $this->close();
        } catch (Throwable) {
        }
    }

    /**
     * @inheritDoc
     */
    public function close(): void
    {
        $resource = $this->getResourceIfNotClosed();

        if (null === $resource) {
            return;
        }

        $this->unlock();

        if (!fclose($resource)) {
            throw new IoException('Could not close resource');
        }
    }

    /**
     * @return resource|null
     */
    private function getResourceIfNotClosed()
    {
        if (is_resource($this->resource)) {
            return $this->resource;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function unlock(): void
    {
        if (!$this->locked) {
            return;
        }

        $resource = $this->getResourceIfNotClosed();

        if (null === $resource) {
            return;
        }

        if (!flock($resource, LOCK_UN)) {
            throw new IoException('Unable to unlock resource');
        }

        $this->locked = false;
    }

    /**
     * @inheritDoc
     */
    public function lock(): void
    {
        if ($this->locked || !$this->lockFlag) {
            return;
        }

        $resource = $this->getResourceIfNotClosed();

        if (null === $resource) {
            return;
        }

        if (!flock($resource, $this->lockFlag)) {
            throw new IoException('Unable to lock resource');
        }

        $this->locked = true;
    }

    /**
     * @inheritDoc
     */
    public function read(): string
    {
        $this->rewind();

        $contents = stream_get_contents($this->getResource());

        if (false === $contents) {
            throw new IoException('Could not read from resource');
        }

        return $contents;
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $resource = $this->getResourceIfNotClosed();

        if (null === $resource) {
            return;
        }

        if (!rewind($resource)) {
            throw new IoException('Could not rewind resource');
        }
    }

    /**
     * @return resource
     */
    private function getResource()
    {
        $resource = $this->getResourceIfNotClosed();

        if (null === $resource) {
            throw new IoException('Resource is already closed');
        }

        return $resource;
    }

    /**
     * @inheritDoc
     */
    public function write(string $contents): void
    {
        $resource = $this->getResource();

        if (!ftruncate($resource, 0)) {
            throw new OutOfBoundsException('Could not truncate resource');
        }

        $this->rewind();

        if (false === fwrite($resource, $contents)) {
            throw new OutOfBoundsException('Could not write to resource');
        }
    }
}
