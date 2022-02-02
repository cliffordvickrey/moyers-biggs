<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Infrastructure\Factory\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\QuestionController;
use CliffordVickrey\MoyersBiggs\Domain\Repository\QuestionnaireRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Domain\Repository\StateRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\QuestionControllerFactory;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Container\MockDependencyInjectionContainer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\QuestionControllerFactory
 */
class QuestionControllerFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testBuild(): void
    {
        $factory = new QuestionControllerFactory();
        $service = $factory->build(new MockDependencyInjectionContainer([
            QuestionnaireRepositoryInterface::class => $this->createMock(QuestionnaireRepositoryInterface::class),
            StateRepositoryInterface::class => $this->createMock(StateRepositoryInterface::class)
        ]));
        self::assertInstanceOf(QuestionController::class, $service);
    }
}
