<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Route\Route;
use CliffordVickrey\MoyersBiggs\Domain\Repository\EventRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Domain\Repository\QuestionnaireRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Config\ConfigProvider;
use CliffordVickrey\MoyersBiggs\Infrastructure\Controller\ControllerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewModel;

use function ceil;
use function is_string;

/**
 * Shows logged questionnaire results
 */
final class LogController implements ControllerInterface
{
    private EventRepositoryInterface $eventRepository;
    private QuestionnaireRepositoryInterface $questionnaireRepository;
    /** @var positive-int */
    private int $recordsPerPage;
    /** @var array<string, string> */
    private array $timeZones;

    /**
     * @param EventRepositoryInterface $eventRepository
     * @param QuestionnaireRepositoryInterface $questionnaireRepository
     * @param positive-int $recordsPerPage
     * @param array<string, string>|null $timeZones
     */
    public function __construct(
        EventRepositoryInterface $eventRepository,
        QuestionnaireRepositoryInterface $questionnaireRepository,
        int $recordsPerPage = 30,
        ?array $timeZones = null
    ) {
        $this->eventRepository = $eventRepository;
        $this->questionnaireRepository = $questionnaireRepository;
        $this->recordsPerPage = $recordsPerPage;
        $this->timeZones = $timeZones ?? ConfigProvider::getTimeZones();
    }

    /**
     * @inheritDoc
     */
    public function dispatch(HttpRequest $request): ViewModel
    {
        $timeZone = $request->getAttribute(Route::ATTRIBUTE_TIME_ZONE) ?? 'est';

        if (!is_string($timeZone) || !isset($this->timeZones[$timeZone])) {
            $timeZone = 'est';
        }

        $questionnaireId = (int)$request->getId(Route::ATTRIBUTE_QUESTIONNAIRE_ID);
        $page = $request->getId(Route::ATTRIBUTE_PAGE) ?: 1;

        $pageCount = (int)ceil($this->eventRepository->getCount($questionnaireId) / $this->recordsPerPage) ?: 1;

        if ($page > $pageCount) {
            $page = $pageCount;
        }

        $offset = ($page - 1) * $this->recordsPerPage;

        $events = $this->eventRepository->getEvents($questionnaireId, $offset, $this->recordsPerPage);

        $viewModel = new ViewModel();
        $viewModel->setPartial('log');
        $viewModel->setParam('js', true);
        $viewModel->setParam('events', $events);
        $viewModel->setParam('page', $page);
        $viewModel->setParam('pageCount', $pageCount);
        $viewModel->setParam('questionnaire', $this->questionnaireRepository->getQuestionnaireById($questionnaireId));
        $viewModel->setParam('timeZone', $timeZone);
        $viewModel->setParam('timeZones', $this->timeZones);
        return $viewModel;
    }
}
