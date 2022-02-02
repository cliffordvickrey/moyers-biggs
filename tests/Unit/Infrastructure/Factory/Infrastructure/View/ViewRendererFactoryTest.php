<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Infrastructure\Factory\Infrastructure\View;

use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\Infrastructure\View\ViewRendererFactory;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewRenderer;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Container\MockDependencyInjectionContainer;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Infrastructure\Factory\Infrastructure\View\ViewRendererFactory
 */
class ViewRendererFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testBuild(): void
    {
        $factory = new ViewRendererFactory();
        $service = $factory->build(new MockDependencyInjectionContainer());
        self::assertInstanceOf(ViewRenderer::class, $service);
    }

    /**
     * @return void
     */
    public function testBuildWithRoot(): void
    {
        $factory = new ViewRendererFactory();
        $service = $factory->build(new MockDependencyInjectionContainer([], ['root' => '/mb/']));
        self::assertInstanceOf(ViewRenderer::class, $service);
    }
}
