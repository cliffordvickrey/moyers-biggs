<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Route\Route;
use CliffordVickrey\MoyersBiggs\Domain\Entity\State;
use CliffordVickrey\MoyersBiggs\Domain\Repository\QuestionnaireRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Controller\ControllerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewModel;

/**
 * Increments the answer frequencies for the stats page
 */
final class SaveFrequenciesController implements ControllerInterface
{
    private QuestionnaireRepositoryInterface $questionnaireRepository;

    /**
     * @param QuestionnaireRepositoryInterface $questionnaireRepository
     */
    public function __construct(QuestionnaireRepositoryInterface $questionnaireRepository)
    {
        $this->questionnaireRepository = $questionnaireRepository;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(HttpRequest $request): ViewModel
    {
        $state = $request->getEntity(State::class);

        $questionnaire = $this->questionnaireRepository->getQuestionnaireById($state->getQuestionnaireId(), true);
        $questionnaire->addFrequencies($state);
        $this->questionnaireRepository->saveQuestionnaireFrequencies($questionnaire);

        $viewModel = new ViewModel();
        $viewModel->setRouteTo(Route::ROUTE_RESULTS);
        return $viewModel;
    }
}
