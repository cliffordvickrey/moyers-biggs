<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Infrastructure\Factory\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\DefaultController;
use CliffordVickrey\MoyersBiggs\Domain\Repository\StateRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\DefaultControllerFactory;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Container\MockDependencyInjectionContainer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\DefaultControllerFactory
 */
class DefaultControllerFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testBuild(): void
    {
        $factory = new DefaultControllerFactory();
        $service = $factory->build(new MockDependencyInjectionContainer([
            StateRepositoryInterface::class => $this->createMock(StateRepositoryInterface::class)
        ]));
        self::assertInstanceOf(DefaultController::class, $service);
    }
}
