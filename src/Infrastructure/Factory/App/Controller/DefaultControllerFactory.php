<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\DefaultController;
use CliffordVickrey\MoyersBiggs\Domain\Repository\StateRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\FactoryInterface;

final class DefaultControllerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @return DefaultController
     */
    public function build(DependencyInjectionContainerInterface $container): DefaultController
    {
        return new DefaultController($container->getService(StateRepositoryInterface::class));
    }
}
