<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\StatsController;
use CliffordVickrey\MoyersBiggs\Domain\Repository\QuestionnaireRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\FactoryInterface;

final class StatsControllerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @return StatsController
     */
    public function build(DependencyInjectionContainerInterface $container): StatsController
    {
        return new StatsController($container->getService(QuestionnaireRepositoryInterface::class));
    }
}
