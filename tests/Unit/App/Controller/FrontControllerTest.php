<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\App\Controller;

use BadMethodCallException;
use CliffordVickrey\MoyersBiggs\App\Controller\DefaultController;
use CliffordVickrey\MoyersBiggs\App\Controller\FrontController;
use CliffordVickrey\MoyersBiggs\App\Route\Route;
use CliffordVickrey\MoyersBiggs\Domain\Repository\StateRepository;
use CliffordVickrey\MoyersBiggs\Infrastructure\Controller\ControllerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewModel;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewRenderer;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewRendererInterface;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Container\MockDependencyInjectionContainer;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Session\MockSession;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\View\MockHeaderEmitter;
use PHPUnit\Framework\TestCase;

use function ob_end_clean;
use function ob_get_contents;
use function ob_start;

/**
 * @covers \CliffordVickrey\MoyersBiggs\App\Controller\FrontController
 */
class FrontControllerTest extends TestCase
{
    private FrontController $frontController;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $naughtyController = new class implements ControllerInterface {
            /**
             * @inheritDoc
             */
            public function dispatch(HttpRequest $request): ViewModel
            {
                throw new BadMethodCallException('naughty!');
            }
        };

        $mockContainer = new MockDependencyInjectionContainer(
            [
                DefaultController::class => new DefaultController(new StateRepository(new MockSession())),
                ViewRendererInterface::class => new ViewRenderer(null, null, null, new MockHeaderEmitter())
            ],
            ['routes' => [
                'blah' => DefaultController::class,
                'invalid' => ViewRendererInterface::class,
                'naughty' => $naughtyController
            ]]
        );
        $this->frontController = new FrontController($mockContainer);
    }

    /**
     * @return void
     */
    public function testDispatch(): void
    {
        $viewModel = $this->frontController->dispatch(new HttpRequest('blah'));
        self::assertEquals('default', $viewModel->getPartial());
    }

    /**
     * @return void
     */
    public function testDispatchNotFound(): void
    {
        $viewModel = $this->frontController->dispatch(new HttpRequest());
        self::assertEquals('error', $viewModel->getPartial());
    }

    /**
     * @return void
     */
    public function testDispatchInvalid(): void
    {
        $viewModel = $this->frontController->dispatch(new HttpRequest('invalid'));
        self::assertEquals('error', $viewModel->getPartial());
    }

    /**
     * @return void
     */
    public function testDispatchException(): void
    {
        $viewModel = $this->frontController->dispatch(new HttpRequest('naughty'));
        self::assertEquals('error', $viewModel->getPartial());
    }

    /**
     * @return void
     */
    public function testDispatchWithDefaultRoutes(): void
    {
        $container = new MockDependencyInjectionContainer([
            DefaultController::class => new DefaultController(new StateRepository(new MockSession())),
            ViewRendererInterface::class => new ViewRenderer()
        ]);

        $frontController = new FrontController($container);

        $viewModel = $frontController->dispatch(new HttpRequest('default'));

        self::assertEquals('default', $viewModel->getPartial());
    }

    /**
     * @return void
     */
    public function testDispatchAndEmit(): void
    {
        ob_start();
        $this->frontController->dispatchAndEmit(new HttpRequest(Route::ROUTE_DEFAULT));
        $contents = (string)ob_get_contents();
        ob_end_clean();
        self::assertStringStartsWith('<!DOCTYPE html>', $contents);
    }
}
