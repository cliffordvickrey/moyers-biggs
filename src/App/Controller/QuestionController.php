<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Route\Route;
use CliffordVickrey\MoyersBiggs\Domain\Entity\Questionnaire;
use CliffordVickrey\MoyersBiggs\Domain\Entity\State;
use CliffordVickrey\MoyersBiggs\Domain\Repository\QuestionnaireRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Domain\Repository\StateRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Controller\ControllerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewModel;

use function count;
use function sprintf;

/**
 * Displays questions and processes answers
 */
final class QuestionController implements ControllerInterface
{
    private QuestionnaireRepositoryInterface $questionnaireRepository;
    private StateRepositoryInterface $stateRepository;

    /**
     * @param QuestionnaireRepositoryInterface $questionnaireRepository
     * @param StateRepositoryInterface $stateRepository
     */
    public function __construct(
        QuestionnaireRepositoryInterface $questionnaireRepository,
        StateRepositoryInterface $stateRepository
    ) {
        $this->questionnaireRepository = $questionnaireRepository;
        $this->stateRepository = $stateRepository;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(HttpRequest $request): ViewModel
    {
        $viewModel = $this->doDispatch($request);

        if (null === $viewModel->getRouteTo() && null === $viewModel->getRedirectTo()) {
            $this->stateRepository->saveSate($request->getEntity(State::class));
        }

        return $viewModel;
    }

    /**
     * @param HttpRequest $request
     * @return ViewModel
     */
    private function doDispatch(HttpRequest $request): ViewModel
    {
        $this->configureRequest($request);
        $questionId = (int)$request->getId(Route::ATTRIBUTE_QUESTION_ID);
        $answerId = $questionId ? $request->getId(Route::ATTRIBUTE_ANSWER_ID) : null;

        $state = $request->getEntity(State::class);
        $stateCount = count($state);

        $viewModel = null;

        if (
            (null === $answerId && $questionId > $stateCount)
            || (null !== $answerId && $questionId > ($stateCount + 1))
        ) {
            // questionnaire is in unexpected state; handle it
            $viewModel = self::dispatchInvalidQuestion($request);
        } elseif (null !== $answerId) {
            // user provided an answer
            $viewModel = self::dispatchWithAnswer($request);
        }

        // no answer provided; likely a new session
        return $viewModel ?? self::dispatchNew($request);
    }

    /**
     * Populates request with state and questionnaire entities
     * @param HttpRequest $request
     * @return void
     */
    private function configureRequest(HttpRequest $request): void
    {
        $state = $this->stateRepository->getState();
        $id = (int)$request->getId(Route::ATTRIBUTE_QUESTIONNAIRE_ID);
        $questionnaire = $this->questionnaireRepository->getQuestionnaireById($id);
        $state->setQuestionnaireId($id);

        $request->setAttribute(Questionnaire::class, $questionnaire);
        $request->setAttribute(Route::ATTRIBUTE_QUESTIONNAIRE_ID, $id);
        $request->setAttribute(State::class, $state);
    }

    /**
     * Redirect when the questionnaire is in an unexpected state
     * @param HttpRequest $request
     * @return ViewModel
     */
    private static function dispatchInvalidQuestion(HttpRequest $request): ViewModel
    {
        $state = $request->getEntity(State::class);
        $viewModel = new ViewModel();
        $viewModel->setRedirectTo($state->isNew() ? '' : sprintf('question/%d', count($state)));
        return $viewModel;
    }

    /**
     * Process a user answer
     * @param HttpRequest $request
     * @return ViewModel|null
     */
    private static function dispatchWithAnswer(HttpRequest $request): ?ViewModel
    {
        $previousAnswerId = null;
        $previousQuestionId = ((int)$request->getId(Route::ATTRIBUTE_QUESTION_ID)) - 1;

        // @codeCoverageIgnoreStart
        if ($previousQuestionId < 0) {
            return null;
        }
        // @codeCoverageIgnoreEnd

        $state = $request->getEntity(State::class);

        if ($state->offsetExists($previousQuestionId)) {
            $previousAnswerId = $state[$previousQuestionId];
        }

        $questionnaire = $request->getEntity(Questionnaire::class);
        $previousQuestion = $questionnaire[$previousQuestionId];

        $answerId = (int)$request->getId(Route::ATTRIBUTE_ANSWER_ID);
        if (!$previousQuestion->offsetExists($answerId)) {
            $request->setAttribute(Route::ATTRIBUTE_QUESTION_ID, $previousQuestionId);
            $request->setAttribute(Route::ATTRIBUTE_ANSWER_ID, $previousAnswerId);
            return self::dispatchInvalidAnswer($request);
        }

        $state[$previousQuestionId] = $answerId;

        if (count($state) === count($questionnaire)) {
            return self::dispatchComplete();
        }

        return null;
    }

    /**
     * Show error message when answer ID not found
     * @param HttpRequest $request
     * @return ViewModel
     */
    private static function dispatchInvalidAnswer(HttpRequest $request): ViewModel
    {
        $questionId = (int)$request->getId(Route::ATTRIBUTE_QUESTION_ID);

        $viewModel = new ViewModel();
        $viewModel->setPartial('question');
        $viewModel->setParam('answerId', $request->getId(Route::ATTRIBUTE_ANSWER_ID));
        $viewModel->setParam('error', 'Invalid answer');
        $viewModel->setParam('question', $request->getEntity(Questionnaire::class)[$questionId]);
        $viewModel->setParam('questionId', $questionId);
        return $viewModel;
    }

    /**
     * Questionnaire complete; log the result
     * @return ViewModel
     */
    private static function dispatchComplete(): ViewModel
    {
        $viewModel = new ViewModel();
        $viewModel->setRouteTo(Route::ROUTE_SAVE_EVENT);
        return $viewModel;
    }

    /**
     * Just show the question
     * @param HttpRequest $request
     * @return ViewModel
     */
    private static function dispatchNew(HttpRequest $request): ViewModel
    {
        $questionId = (int)$request->getId(Route::ATTRIBUTE_QUESTION_ID);

        $viewModel = new ViewModel();
        $viewModel->setPartial('question');
        $viewModel->setParam('question', $request->getEntity(Questionnaire::class)[$questionId]);
        $viewModel->setParam('questionId', $questionId);

        $state = $request->getEntity(State::class);
        if ($state->offsetExists($questionId)) {
            $viewModel->setParam('answerId', $state[$questionId]);
        }

        return $viewModel;
    }
}
