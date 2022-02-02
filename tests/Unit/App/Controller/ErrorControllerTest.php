<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\ErrorController;
use CliffordVickrey\MoyersBiggs\App\Exception\UserException;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @covers \CliffordVickrey\MoyersBiggs\App\Controller\ErrorController
 */
class ErrorControllerTest extends TestCase
{
    /**
     * @return void
     */
    public function testDispatch(): void
    {
        $errorController = new ErrorController('something', 422);
        $viewModel = $errorController->dispatch(new HttpRequest());
        self::assertEquals('something', $viewModel->getParams()['message']);
        self::assertEquals(422, $viewModel->getParams()['code']);
        self::assertEquals('error', $viewModel->getPartial());
        self::assertEquals(422, $viewModel->getStatusCode());
    }

    /**
     * @return void
     */
    public function testNotFound(): void
    {
        $errorController = ErrorController::notFound();
        $viewModel = $errorController->dispatch(new HttpRequest());
        self::assertEquals(404, $viewModel->getStatusCode());
    }

    /**
     * @return void
     */
    public function testFromThrowable(): void
    {
        $errorController = ErrorController::fromThrowable(new RuntimeException('ruh roh'));
        $viewModel = $errorController->dispatch(new HttpRequest());
        self::assertEquals(500, $viewModel->getStatusCode());
        self::assertEquals('There was an unhandled exception', $viewModel->getParams()['message']);

        $errorController = ErrorController::fromThrowable(new RuntimeException('ruh roh'), true);
        $viewModel = $errorController->dispatch(new HttpRequest());
        self::assertEquals(500, $viewModel->getStatusCode());
        /** @var string $message */
        $message = $viewModel->getParams()['message'];
        self::assertStringStartsWith('RuntimeException: ruh roh', $message);

        $errorController = ErrorController::fromThrowable(new UserException('ruh roh'));
        $viewModel = $errorController->dispatch(new HttpRequest());
        self::assertEquals(422, $viewModel->getStatusCode());
        self::assertEquals('ruh roh', $viewModel->getParams()['message']);
    }
}
