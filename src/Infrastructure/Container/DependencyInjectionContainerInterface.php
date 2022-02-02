<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Container;

/**
 * Resolves services, wired with constructor dependencies, used by the application
 */
interface DependencyInjectionContainerInterface
{
    /**
     * Resolve object of type T
     * @template T of object
     * @param class-string<T> $name
     * @return T
     */
    public function getService(string $name): object;

    /**
     * Get the application config
     * @return array<string, mixed>
     */
    public function getConfig(): array;
}
