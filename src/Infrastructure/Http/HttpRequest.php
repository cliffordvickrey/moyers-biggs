<?php

/** @noinspection PhpDocSignatureInspection */

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Http;

use CliffordVickrey\MoyersBiggs\App\Route\Route;
use CliffordVickrey\MoyersBiggs\Domain\Exception\UnexpectedValueException;

use function is_numeric;
use function sprintf;

/**
 * Encapsulates a request's attributes, similar to PSR-7/PSR-15 messaging objects
 */
final class HttpRequest
{
    /** @var array<string, mixed> */
    private array $attributes;
    private string $route;

    /**
     * @param string $route
     * @param array<string, mixed> $attributes
     */
    public function __construct(string $route = Route::ROUTE_NONE, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getAttribute(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setAttribute(string $name, mixed $value): void
    {
        $this->attributes[$name] = $value;
    }

    /**
     * @param string $name
     * @return int<0, max>|null
     */
    public function getId(string $name): ?int
    {
        $value = $this->attributes[$name] ?? null;

        if (!is_numeric($value)) {
            return null;
        }

        $value = (int)$value;

        if ($value < 0) {
            return null;
        }

        return $value;
    }

    /**
     * @template T of object
     * @param class-string<T> $name
     * @return T
     */
    public function getEntity(string $name)
    {
        $value = $this->attributes[$name] ?? null;

        if ($value instanceof $name) {
            return $value;
        }

        throw new UnexpectedValueException(sprintf('Expected instance of %s', $name));
    }
}
