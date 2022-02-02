<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\QuestionController;
use CliffordVickrey\MoyersBiggs\App\Route\Route;
use CliffordVickrey\MoyersBiggs\Domain\Entity\Question;
use CliffordVickrey\MoyersBiggs\Domain\Repository\StateRepository;
use CliffordVickrey\MoyersBiggs\Infrastructure\Clock\Clock;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Domain\Repository\MockQuestionnaireRepository;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Session\MockSession;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\App\Controller\QuestionController
 */
class QuestionControllerTest extends TestCase
{
    private QuestionController $questionController;
    private StateRepository $stateRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        /** @var DateTimeImmutable $dateTime */
        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d', '2022-01-01');
        $clock = new Clock($dateTime);
        $this->stateRepository = new StateRepository(new MockSession(), $clock);
        $this->questionController = new QuestionController(new MockQuestionnaireRepository(), $this->stateRepository);
    }

    /**
     * @return void
     */
    public function testDispatchNew(): void
    {
        $viewModel = $this->questionController->dispatch(new HttpRequest());

        $params = $viewModel->getParams();

        self::assertEquals(0, $params['questionId']);

        /** @var Question $question */
        $question = $params['question'];

        self::assertInstanceOf(Question::class, $question);
        self::assertEquals('Q1', $question->getText());
    }

    /**
     * @return void
     */
    public function testDispatchWithAnswer(): void
    {
        $viewModel = $this->questionController->dispatch(new HttpRequest(Route::ROUTE_QUESTION, [
            Route::ATTRIBUTE_ANSWER_ID => 1,
            Route::ATTRIBUTE_QUESTION_ID => 1
        ]));

        $params = $viewModel->getParams();

        /** @var Question $question */
        $question = $params['question'];

        self::assertInstanceOf(Question::class, $question);
        self::assertEquals('Q2', $question->getText());
    }

    /**
     * @return void
     */
    public function testDispatchWithState(): void
    {
        $state = $this->stateRepository->getState();
        $state[] = 0;
        $this->stateRepository->saveSate($state);

        $viewModel = $this->questionController->dispatch(new HttpRequest(Route::ROUTE_QUESTION, [
            Route::ATTRIBUTE_QUESTION_ID => 0
        ]));

        $params = $viewModel->getParams();

        /** @var Question $question */
        $question = $params['question'];

        self::assertInstanceOf(Question::class, $question);
        self::assertEquals('Q1', $question->getText());
    }

    /**
     * @return void
     */
    public function testDispatchComplete(): void
    {
        $state = $this->stateRepository->getState();
        $state[] = 0;
        $this->stateRepository->saveSate($state);

        $viewModel = $this->questionController->dispatch(new HttpRequest(Route::ROUTE_QUESTION, [
            Route::ATTRIBUTE_ANSWER_ID => 1,
            Route::ATTRIBUTE_QUESTION_ID => 2
        ]));

        self::assertEquals('saveEvent', $viewModel->getRouteTo());
    }

    /**
     * @return void
     */
    public function testDispatchInvalidState(): void
    {
        $viewModel = $this->questionController->dispatch(new HttpRequest(Route::ROUTE_QUESTION, [
            Route::ATTRIBUTE_ANSWER_ID => 1,
            Route::ATTRIBUTE_QUESTION_ID => 2
        ]));

        self::assertEquals('', $viewModel->getRedirectTo());
    }

    /**
     * @return void
     */
    public function testDispatchInvalidAnswer(): void
    {
        $viewModel = $this->questionController->dispatch(new HttpRequest(Route::ROUTE_QUESTION, [
            Route::ATTRIBUTE_ANSWER_ID => 2,
            Route::ATTRIBUTE_QUESTION_ID => 1
        ]));

        $params = $viewModel->getParams();

        self::assertEquals('Invalid answer', $params['error']);
    }

    /**
     * @return void
     */
    public function testDispatchInvalidAnswerWithPreviousAnswer(): void
    {
        $state = $this->stateRepository->getState();
        $state[] = 0;
        $this->stateRepository->saveSate($state);

        $viewModel = $this->questionController->dispatch(new HttpRequest(Route::ROUTE_QUESTION, [
            Route::ATTRIBUTE_ANSWER_ID => 2,
            Route::ATTRIBUTE_QUESTION_ID => 1
        ]));

        $params = $viewModel->getParams();

        self::assertEquals('Invalid answer', $params['error']);
        self::assertEquals(0, $params['answerId']);
    }
}
