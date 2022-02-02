<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Infrastructure\Factory\Domain\Repository;

use CliffordVickrey\MoyersBiggs\Domain\Entity\Questionnaire;
use CliffordVickrey\MoyersBiggs\Domain\Exception\UnexpectedValueException;
use CliffordVickrey\MoyersBiggs\Domain\Repository\QuestionnaireRepository;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\Domain\Repository\QuestionnaireRepositoryFactory;
use CliffordVickrey\MoyersBiggs\Infrastructure\Io\IoInterface;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Container\MockDependencyInjectionContainer;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Io\MockIo;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Infrastructure\Factory\Domain\Repository\QuestionnaireRepositoryFactory
 */
class QuestionnaireRepositoryFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testBuild(): void
    {
        $factory = new QuestionnaireRepositoryFactory();
        $service = $factory->build(new MockDependencyInjectionContainer([
            IoInterface::class => new MockIo()
        ], [Questionnaire::class => []]));
        self::assertInstanceOf(QuestionnaireRepository::class, $service);
    }

    /**
     * @return void
     */
    public function testBuildNoConfig(): void
    {
        $factory = new QuestionnaireRepositoryFactory();

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Expected array; got NULL');
        $factory->build(new MockDependencyInjectionContainer([
            IoInterface::class => new MockIo()
        ]));
    }
}
