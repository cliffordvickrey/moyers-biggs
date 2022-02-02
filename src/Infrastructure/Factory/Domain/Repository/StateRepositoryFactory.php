<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Factory\Domain\Repository;

use CliffordVickrey\MoyersBiggs\Domain\Repository\StateRepository;
use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\FactoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Session\SessionInterface;

final class StateRepositoryFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @return StateRepository
     */
    public function build(DependencyInjectionContainerInterface $container): StateRepository
    {
        return new StateRepository($container->getService(SessionInterface::class));
    }
}
