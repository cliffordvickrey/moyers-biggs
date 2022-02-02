<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\QuestionController;
use CliffordVickrey\MoyersBiggs\Domain\Repository\QuestionnaireRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Domain\Repository\StateRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\FactoryInterface;

final class QuestionControllerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @return QuestionController
     */
    public function build(DependencyInjectionContainerInterface $container): QuestionController
    {
        return new QuestionController(
            $container->getService(QuestionnaireRepositoryInterface::class),
            $container->getService(StateRepositoryInterface::class)
        );
    }
}
