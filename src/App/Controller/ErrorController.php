<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Exception\UserException;
use CliffordVickrey\MoyersBiggs\Infrastructure\Controller\ControllerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewModel;
use JetBrains\PhpStorm\Pure;
use Throwable;

/**
 * Displays an error message
 */
final class ErrorController implements ControllerInterface
{
    private ?string $message;
    private int $code;

    /**
     * @param string|null $message
     * @param int $code
     */
    public function __construct(?string $message = null, int $code = 500)
    {
        $this->message = $message;
        $this->code = $code;
    }

    /**
     * @return ErrorController
     */
    #[Pure]
    public static function notFound(): self
    {
        return new self(null, 404);
    }

    /**
     * @param Throwable $ex
     * @param bool $debug
     * @return ErrorController
     */
    public static function fromThrowable(Throwable $ex, bool $debug = false): self
    {
        if ($ex instanceof UserException) {
            return new self($ex->getMessage(), 422);
        }

        $message = $debug ? ((string)$ex) : 'There was an unhandled exception';
        /** @noinspection PhpCastIsUnnecessaryInspection */
        return new self($message, (int)($ex->getCode() ?: 500));
    }

    /**
     * @inheritDoc
     */
    public function dispatch(HttpRequest $request): ViewModel
    {
        $viewModel = new ViewModel();
        $viewModel->setParam('message', $this->message);
        $viewModel->setParam('code', $this->code);
        $viewModel->setPartial('error');
        $viewModel->setStatusCode($this->code);
        return $viewModel;
    }
}
