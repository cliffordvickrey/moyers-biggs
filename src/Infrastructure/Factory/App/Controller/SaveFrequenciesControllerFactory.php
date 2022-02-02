<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\SaveFrequenciesController;
use CliffordVickrey\MoyersBiggs\Domain\Repository\QuestionnaireRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\FactoryInterface;

final class SaveFrequenciesControllerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @return SaveFrequenciesController
     */
    public function build(DependencyInjectionContainerInterface $container): SaveFrequenciesController
    {
        return new SaveFrequenciesController($container->getService(QuestionnaireRepositoryInterface::class));
    }
}
