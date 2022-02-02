<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Infrastructure\Factory\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\FrontController;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\FrontControllerFactory;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Container\MockDependencyInjectionContainer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\FrontControllerFactory
 */
class FrontControllerFactoryTest extends TestCase
{
    public function testBuild(): void
    {
        $factory = new FrontControllerFactory();
        $service = $factory->build(new MockDependencyInjectionContainer());
        self::assertInstanceOf(FrontController::class, $service);
    }
}
