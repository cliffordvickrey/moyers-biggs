<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\App\Controller;

use CliffordVickrey\MoyersBiggs\Domain\Repository\QuestionnaireRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Controller\ControllerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewModel;

/**
 * Show the stats page
 */
final class StatsController implements ControllerInterface
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
        $questionnaire = $this->questionnaireRepository->getQuestionnaireById(0, true);

        $viewModel = new ViewModel();
        $viewModel->setPartial('stats');
        $viewModel->setParam('js', true);
        $viewModel->setParam('questionnaire', $questionnaire);
        return $viewModel;
    }
}
