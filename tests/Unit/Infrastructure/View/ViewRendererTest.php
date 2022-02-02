<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Infrastructure\View;

use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewModel;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewRenderer;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\View\MockHeaderEmitter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewRenderer
 */
class ViewRendererTest extends TestCase
{
    private MockHeaderEmitter $headerEmitter;
    private ViewRenderer $viewRenderer;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->headerEmitter = new MockHeaderEmitter();
        $this->viewRenderer = new ViewRenderer(null, __DIR__, $this->headerEmitter);
    }

    /**
     * @return void
     */
    public function testRender(): void
    {
        $viewModel = new ViewModel();
        $viewModel->setPartial('view');
        $contents = $this->viewRenderer->render($viewModel);
        self::assertEquals('<div>Hello world!</div>', $contents);

        $expectedHeaders = [
            'Content-Type' => [
                'text/html; charset=utf-8'
            ],
            'Cache-Control' => [
                'no-store, no-cache, must-revalidate, max-age=0',
                'post-check=0, pre-check=0'
            ]
        ];

        self::assertEquals($expectedHeaders, $this->headerEmitter->headers);
        self::assertEquals(200, $this->headerEmitter->status);
    }

    /**
     * @return void
     */
    public function testRenderRedirect(): void
    {
        $viewModel = new ViewModel();
        $viewModel->setRedirectTo('test');
        $contents = $this->viewRenderer->render($viewModel);
        self::assertEquals('', $contents);
        self::assertEquals(302, $this->headerEmitter->status);

        $expectedHeaders = [
            'Content-Type' => [
                'text/html; charset=utf-8',
            ],
            'Cache-Control' => [
                'no-store, no-cache, must-revalidate, max-age=0',
                'post-check=0, pre-check=0'
            ],
            'Location' => [
                '/test',
            ]
        ];

        self::assertEquals($expectedHeaders, $this->headerEmitter->headers);
    }
}
