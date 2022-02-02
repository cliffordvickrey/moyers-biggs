<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Container;

use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Exception\ContainerException;

use function get_class;
use function sprintf;

class MockDependencyInjectionContainer implements DependencyInjectionContainerInterface
{
    /** @var array<string, object> */
    private array $services;
    /** @var array<string, mixed> */
    private array $config;

    /**
     * @param array<string, object> $services
     * @param array<string, mixed> $config
     */
    public function __construct(array $services = [], array $config = [])
    {
        $this->services = $services;
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function getService(string $name): object
    {
        $service = $this->services[$name] ?? null;

        if (null === $service) {
            throw new ContainerException(sprintf('Service %s does not exist', $name));
        }

        if ($service instanceof $name) {
            return $service;
        }

        throw new ContainerException(sprintf('Object (%s) is not an instance of %s', get_class($service), $name));
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}
