<?php

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Domain\Entity;

use ArrayAccess;
use ArrayIterator;
use CliffordVickrey\MoyersBiggs\Domain\Exception\OutOfBoundException;
use Countable;
use IteratorAggregate;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;

use function array_merge;
use function array_slice;
use function array_unshift;
use function array_values;
use function count;
use function is_object;
use function is_scalar;
use function sprintf;

/**
 * Represents more than one of something (i.e., type T)
 *
 * @template T
 * @implements ArrayAccess<int, T>
 * @implements IteratorAggregate<int, T>
 */
abstract class Collection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    /** @var list<T> */
    protected array $values;

    /**
     * @param T ...$values
     */
    public function __construct(...$values)
    {
        $this->values = array_values($values);
    }

    /**
     * @return void
     */
    public function __clone(): void
    {
        foreach ($this->values as $i => $value) {
            if (!is_object($value)) {
                continue;
            }

            $this->values[$i] = clone $value;
        }
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->values);
    }

    /**
     * @return ArrayIterator<int, T>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->values);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    #[Pure]
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->values[self::normalizeOffset($offset)]);
    }

    /**
     * @param mixed $offset
     * @return int
     */
    private static function normalizeOffset(mixed $offset): int
    {
        if (is_scalar($offset)) {
            return (int)$offset;
        }

        return 0;
    }

    /**
     * @param int $offset
     * @param int|null $length
     * @return void
     */
    public function slice(int $offset, ?int $length = null): void
    {
        $this->values = array_slice($this->values, $offset, $length);
    }

    /**
     * @param list<T> $data
     * @return void
     */
    public function merge(array $data): void
    {
        $this->values = array_merge($this->values, $data);
    }

    /**
     * @param mixed $offset
     * @return T
     */
    public function offsetGet(mixed $offset): mixed
    {
        $offset = self::normalizeOffset($offset);

        if (!isset($this->values[$offset])) {
            throw new OutOfBoundException(sprintf('Could not fetch %s value with offset %d', static::class, $offset));
        }

        return $this->values[$offset];
    }

    /**
     * @param mixed $offset
     * @param T $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (null === $offset) {
            $this->values[] = $value;
            return;
        }

        $this->values[self::normalizeOffset($offset)] = $value;
    }

    /**
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->values[self::normalizeOffset($offset)]);
    }

    /**
     * @param T $value
     * @return void
     */
    public function unshift(mixed $value): void
    {
        array_unshift($this->values, $value);
    }

    /**
     * @return list<T>
     */
    public function toArray(): array
    {
        return $this->values;
    }

    /**
     * @return array<mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->values;
    }
}
