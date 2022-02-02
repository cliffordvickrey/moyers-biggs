<?php

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Infrastructure\Factory\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\SaveFrequenciesController;
use CliffordVickrey\MoyersBiggs\Domain\Repository\QuestionnaireRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\SaveFrequenciesControllerFactory;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Container\MockDependencyInjectionContainer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\SaveFrequenciesControllerFactory
 */
class SaveFrequenciesControllerFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testBuild(): void
    {
        $factory = new SaveFrequenciesControllerFactory();
        $service = $factory->build(new MockDependencyInjectionContainer([
            QuestionnaireRepositoryInterface::class => $this->createMock(QuestionnaireRepositoryInterface::class)
        ]));
        self::assertInstanceOf(SaveFrequenciesController::class, $service);
    }
}
