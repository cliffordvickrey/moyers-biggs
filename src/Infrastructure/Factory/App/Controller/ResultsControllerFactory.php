<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\ResultsController;
use CliffordVickrey\MoyersBiggs\Domain\Repository\StateRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\FactoryInterface;

final class ResultsControllerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @return ResultsController
     */
    public function build(DependencyInjectionContainerInterface $container): ResultsController
    {
        return new ResultsController($container->getService(StateRepositoryInterface::class));
    }
}
