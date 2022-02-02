<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Exception\AppException;
use CliffordVickrey\MoyersBiggs\App\Route\Route;
use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Controller\ControllerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewModel;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewRendererInterface;
use JetBrains\PhpStorm\ArrayShape;
use Throwable;

use function class_implements;
use function get_class;
use function in_array;
use function is_array;
use function is_object;
use function sprintf;

/**
 * The front controller. Handles all requests
 */
final class FrontController implements ControllerInterface
{
    private bool $debug;
    private DependencyInjectionContainerInterface $container;
    /** @var array<string, class-string|object> */
    private array $routes;

    /**
     * @param DependencyInjectionContainerInterface $container
     */
    public function __construct(DependencyInjectionContainerInterface $container)
    {
        $this->container = $container;
        $this->configure();
    }

    /**
     * @return void
     */
    private function configure(): void
    {
        $config = $this->container->getConfig();

        if (isset($config['routes']) && is_array($config['routes'])) {
            $routes = $config['routes'];
        } else {
            $routes = self::getDefaultRoutes();
        }

        /** @var array<string, class-string> $routes */
        $this->routes = $routes;
        $this->debug = (bool)($config['debug'] ?? false);
    }

    /**
     * @return array<string, class-string>
     */
    #[ArrayShape([
        Route::ROUTE_DEFAULT => "string",
        Route::ROUTE_LOG => "string",
        Route::ROUTE_QUESTION => "string",
        Route::ROUTE_RESULTS => "string",
        Route::ROUTE_SAVE_EVENT => "string",
        Route::ROUTE_SAVE_FREQUENCIES => "string",
        Route::ROUTE_STATS => "string"
    ])]
    private static function getDefaultRoutes(): array
    {
        return [
            Route::ROUTE_DEFAULT => DefaultController::class,
            Route::ROUTE_LOG => LogController::class,
            Route::ROUTE_QUESTION => QuestionController::class,
            Route::ROUTE_RESULTS => ResultsController::class,
            Route::ROUTE_SAVE_EVENT => SaveEventController::class,
            Route::ROUTE_SAVE_FREQUENCIES => SaveFrequenciesController::class,
            Route::ROUTE_STATS => StatsController::class
        ];
    }

    /**
     * @param HttpRequest $request
     * @return void
     */
    public function dispatchAndEmit(HttpRequest $request): void
    {
        $viewRenderer = $this->container->getService(ViewRendererInterface::class);

        try {
            $output = $viewRenderer->render($this->dispatch($request));
            // @codeCoverageIgnoreStart
        } catch (Throwable $ex) {
            $errorController = ErrorController::fromThrowable($ex, $this->debug);
            $output = $viewRenderer->render($errorController->dispatch($request));
        }
        // @codeCoverageIgnoreEnd

        echo $output;
    }

    /**
     * Loops until request is dispatched
     * @inheritDoc
     */
    public function dispatch(HttpRequest $request): ViewModel
    {
        $viewModel = new ViewModel();
        $viewModel->setRouteTo($request->getRoute());

        do {
            $viewModel = $this->doDispatch($request, (string)$viewModel->getRouteTo());
        } while (null !== $viewModel->getRouteTo());

        return $viewModel;
    }

    /**
     * @param HttpRequest $request
     * @param string $route
     * @return ViewModel
     */
    private function doDispatch(HttpRequest $request, string $route): ViewModel
    {
        try {
            return $this->resolveController($route)->dispatch($request);
        } catch (Throwable $ex) {
            $errorController = ErrorController::fromThrowable($ex, $this->debug);
        }

        return $errorController->dispatch($request);
    }

    /**
     * Constructs a controller from a route name
     * @param string $route
     * @return ControllerInterface
     */
    private function resolveController(string $route): ControllerInterface
    {
        $className = $this->routes[$route] ?? null;

        if (null === $className) {
            return ErrorController::notFound();
        }

        if (
            (is_object($className) && !($className instanceof ControllerInterface))
            || !in_array(ControllerInterface::class, class_implements($className) ?: [])
        ) {
            throw new AppException(sprintf(
                'Controller class %s does not implement %s',
                is_object($className) ? get_class($className) : $className,
                ControllerInterface::class
            ));
        }

        if (is_object($className)) {
            /** @var ControllerInterface $className */
            return $className;
        }

        /** @var ControllerInterface $controller */
        /** @noinspection PhpUnnecessaryLocalVariableInspection */
        $controller = $this->container->getService($className);
        return $controller;
    }
}
