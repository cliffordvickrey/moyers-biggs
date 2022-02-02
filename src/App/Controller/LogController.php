<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Route\Route;
use CliffordVickrey\MoyersBiggs\Domain\Repository\EventRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Domain\Repository\QuestionnaireRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Controller\ControllerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewModel;

use function ceil;

/**
 * Shows logged questionnaire results
 */
final class LogController implements ControllerInterface
{
    private EventRepositoryInterface $eventRepository;
    private QuestionnaireRepositoryInterface $questionnaireRepository;
    /** @var positive-int */
    private int $recordsPerPage;

    /**
     * @param EventRepositoryInterface $eventRepository
     * @param QuestionnaireRepositoryInterface $questionnaireRepository
     * @param positive-int $recordsPerPage
     */
    public function __construct(
        EventRepositoryInterface $eventRepository,
        QuestionnaireRepositoryInterface $questionnaireRepository,
        int $recordsPerPage = 30
    ) {
        $this->eventRepository = $eventRepository;
        $this->questionnaireRepository = $questionnaireRepository;
        $this->recordsPerPage = $recordsPerPage;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(HttpRequest $request): ViewModel
    {
        $questionnaireId = (int)$request->getId(Route::ATTRIBUTE_QUESTIONNAIRE_ID);
        $page = $request->getId(Route::ATTRIBUTE_PAGE) ?: 1;

        $offset = ($page - 1) * $this->recordsPerPage;

        $events = $this->eventRepository->getEvents($questionnaireId, $offset, $this->recordsPerPage);
        $pageCount = (int)(ceil($this->eventRepository->getCount($questionnaireId) / $this->recordsPerPage));

        $viewModel = new ViewModel();
        $viewModel->setPartial('log');
        $viewModel->setParam('js', true);
        $viewModel->setParam('events', $events);
        $viewModel->setParam('page', $page);
        $viewModel->setParam('pageCount', $pageCount ?: 1);
        $viewModel->setParam('questionnaire', $this->questionnaireRepository->getQuestionnaireById($questionnaireId));
        return $viewModel;
    }
}
