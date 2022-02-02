<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\App\Controller;

use CliffordVickrey\MoyersBiggs\Domain\Repository\StateRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Controller\ControllerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewModel;

/**
 * The main page
 */
final class DefaultController implements ControllerInterface
{
    private StateRepositoryInterface $stateRepository;

    /**
     * @param StateRepositoryInterface $session
     */
    public function __construct(StateRepositoryInterface $session)
    {
        $this->stateRepository = $session;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(HttpRequest $request): ViewModel
    {
        $this->stateRepository->deleteState();

        $viewModel = new ViewModel();
        $viewModel->setPartial('default');
        return $viewModel;
    }
}
