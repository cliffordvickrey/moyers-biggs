<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Factory;

use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainerInterface;

interface FactoryInterface
{
    /**
     * @param DependencyInjectionContainerInterface $container
     * @return object
     */
    public function build(DependencyInjectionContainerInterface $container): object;
}
