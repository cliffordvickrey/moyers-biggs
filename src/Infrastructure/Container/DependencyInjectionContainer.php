<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Container;

use CliffordVickrey\MoyersBiggs\Infrastructure\Exception\ContainerException;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\FactoryInterface;

use function class_exists;
use function get_class;
use function is_array;
use function sprintf;

class DependencyInjectionContainer implements DependencyInjectionContainerInterface
{
    /** @var array<string, string>|null */
    private ?array $aliases = null;
    /** @var array<string, mixed> */
    private array $config;
    /** @var array<string, string>|null */
    private ?array $factories = null;
    /** @var array<string, object> */
    private array $services = [];

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @template T of object
     * @param class-string<T> $name
     * @return T
     */
    public function getService(string $name): object
    {
        if (isset($this->services[$name])) {
            /** @var T $service */
            /** @noinspection PhpUnnecessaryLocalVariableInspection */
            $service = $this->services[$name];
            return $service;
        }

        $service = $this->resolveService($name);

        if (null === $service) {
            throw new ContainerException(sprintf('Class %s does not exist', $name));
        }

        if (!($service instanceof $name)) {
            throw new ContainerException(sprintf('Object (%s) is not an instance of %s', get_class($service), $name));
        }

        $this->services[$name] = $service;
        return $service;
    }

    /**
     * @param class-string $name
     * @return object|null
     */
    private function resolveService(string $name): ?object
    {
        $factories = $this->getFactories();

        if (isset($factories[$name])) {
            return $this->getFactory($factories[$name])->build($this);
        }

        $aliases = $this->getAliases();

        if (isset($aliases[$name])) {
            $name = $aliases[$name];
        }

        if (class_exists($name)) {
            return new $name();
        }

        return null;
    }

    /**
     * @return array<string, string>
     */
    private function getFactories(): array
    {
        if (null !== $this->factories) {
            return $this->factories;
        }

        $containerConfig = $this->getContainerConfig();
        $factories = $containerConfig['factories'] ?? [];

        if (!is_array($factories)) {
            $factories = [];
        }

        $this->factories = $factories;
        return $factories;
    }

    /**
     * @return array<string, mixed>
     */
    private function getContainerConfig(): array
    {
        $containerConfig = $this->config[DependencyInjectionContainerInterface::class] ?? [];

        if (!is_array($containerConfig)) {
            return [];
        }

        return $containerConfig;
    }

    /**
     * @param string $name
     * @return FactoryInterface
     */
    private function getFactory(string $name): FactoryInterface
    {
        $factory = new $name();

        if ($factory instanceof FactoryInterface) {
            return $factory;
        }

        $message = sprintf('Class %s does not implement %s', $name, FactoryInterface::class);
        throw new ContainerException($message);
    }

    /**
     * @return array<string, string>
     */
    private function getAliases(): array
    {
        if (null !== $this->aliases) {
            return $this->aliases;
        }

        $containerConfig = $this->getContainerConfig();
        $aliases = $containerConfig['aliases'] ?? [];

        if (!is_array($aliases)) {
            $aliases = [];
        }

        $this->aliases = $aliases;
        return $aliases;
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}
