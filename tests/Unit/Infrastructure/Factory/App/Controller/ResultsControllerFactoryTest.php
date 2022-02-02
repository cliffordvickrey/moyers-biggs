<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Infrastructure\Factory\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\ResultsController;
use CliffordVickrey\MoyersBiggs\Domain\Repository\StateRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\ResultsControllerFactory;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Container\MockDependencyInjectionContainer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\ResultsControllerFactory
 */
class ResultsControllerFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testBuild(): void
    {
        $factory = new ResultsControllerFactory();
        $service = $factory->build(new MockDependencyInjectionContainer([
            StateRepositoryInterface::class => $this->createMock(StateRepositoryInterface::class)
        ]));
        self::assertInstanceOf(ResultsController::class, $service);
    }
}
