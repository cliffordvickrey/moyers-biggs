<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Factory\Domain\Repository;

use CliffordVickrey\MoyersBiggs\Domain\Repository\EventRepository;
use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\FactoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Io\IoInterface;

final class EventRepositoryFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @return EventRepository
     */
    public function build(DependencyInjectionContainerInterface $container): EventRepository
    {
        return new EventRepository(null, $container->getService(IoInterface::class));
    }
}
