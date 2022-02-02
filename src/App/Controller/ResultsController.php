<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\App\Controller;

use CliffordVickrey\MoyersBiggs\Domain\Entity\Questionnaire;
use CliffordVickrey\MoyersBiggs\Domain\Entity\State;
use CliffordVickrey\MoyersBiggs\Domain\Repository\StateRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Controller\ControllerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewModel;

/**
 * Show users their test results
 */
final class ResultsController implements ControllerInterface
{
    private StateRepositoryInterface $stateRepository;

    /**
     * @param StateRepositoryInterface $stateRepository
     */
    public function __construct(StateRepositoryInterface $stateRepository)
    {
        $this->stateRepository = $stateRepository;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(HttpRequest $request): ViewModel
    {
        $this->stateRepository->deleteState();

        $questionnaire = $request->getEntity(Questionnaire::class);
        $state = $request->getEntity(State::class);

        $viewModel = new ViewModel();
        $viewModel->setParam('score', $questionnaire->getScore($state));
        $viewModel->setPartial('results');
        return $viewModel;
    }
}
