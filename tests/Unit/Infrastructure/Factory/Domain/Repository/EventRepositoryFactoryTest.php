<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Infrastructure\Factory\Domain\Repository;

use CliffordVickrey\MoyersBiggs\Domain\Repository\EventRepository;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\Domain\Repository\EventRepositoryFactory;
use CliffordVickrey\MoyersBiggs\Infrastructure\Io\IoInterface;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Container\MockDependencyInjectionContainer;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Io\MockIo;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Infrastructure\Factory\Domain\Repository\EventRepositoryFactory
 */
class EventRepositoryFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testBuild(): void
    {
        $factory = new EventRepositoryFactory();
        $service = $factory->build(new MockDependencyInjectionContainer([
            IoInterface::class => new MockIo()
        ]));
        self::assertInstanceOf(EventRepository::class, $service);
    }
}
