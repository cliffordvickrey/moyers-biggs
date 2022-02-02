<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\FrontController;
use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\FactoryInterface;

final class FrontControllerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @return FrontController
     */
    public function build(DependencyInjectionContainerInterface $container): FrontController
    {
        return new FrontController($container);
    }
}
