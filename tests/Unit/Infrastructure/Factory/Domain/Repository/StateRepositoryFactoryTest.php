<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Infrastructure\Factory\Domain\Repository;

use CliffordVickrey\MoyersBiggs\Domain\Repository\StateRepository;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\Domain\Repository\StateRepositoryFactory;
use CliffordVickrey\MoyersBiggs\Infrastructure\Session\SessionInterface;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Container\MockDependencyInjectionContainer;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Session\MockSession;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Infrastructure\Factory\Domain\Repository\StateRepositoryFactory
 */
class StateRepositoryFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testBuild(): void
    {
        $factory = new StateRepositoryFactory();
        $service = $factory->build(new MockDependencyInjectionContainer([
            SessionInterface::class => new MockSession()
        ]));
        self::assertInstanceOf(StateRepository::class, $service);
    }
}
