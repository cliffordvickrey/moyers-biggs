<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Infrastructure\Factory\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\LogController;
use CliffordVickrey\MoyersBiggs\Domain\Repository\EventRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Domain\Repository\QuestionnaireRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\LogControllerFactory;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Container\MockDependencyInjectionContainer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\LogControllerFactory
 */
class LogControllerFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testBuild(): void
    {
        $factory = new LogControllerFactory();
        $service = $factory->build(new MockDependencyInjectionContainer([
            EventRepositoryInterface::class => $this->createMock(EventRepositoryInterface::class),
            QuestionnaireRepositoryInterface::class => $this->createMock(QuestionnaireRepositoryInterface::class)
        ], [LogController::class => ['recordsPerPage' => -1]]));
        self::assertInstanceOf(LogController::class, $service);
    }

    /**
     * @return void
     */
    public function testBuildDefaultConfig(): void
    {
        $factory = new LogControllerFactory();
        $service = $factory->build(new MockDependencyInjectionContainer([
            EventRepositoryInterface::class => $this->createMock(EventRepositoryInterface::class),
            QuestionnaireRepositoryInterface::class => $this->createMock(QuestionnaireRepositoryInterface::class)
        ]));
        self::assertInstanceOf(LogController::class, $service);
    }
}
