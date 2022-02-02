<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\SaveEventController;
use CliffordVickrey\MoyersBiggs\Domain\Repository\EventRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\FactoryInterface;

final class SaveEventControllerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @return SaveEventController
     */
    public function build(DependencyInjectionContainerInterface $container): SaveEventController
    {
        return new SaveEventController($container->getService(EventRepositoryInterface::class));
    }
}
