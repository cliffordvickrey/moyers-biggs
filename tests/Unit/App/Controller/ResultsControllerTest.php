<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\ResultsController;
use CliffordVickrey\MoyersBiggs\App\Route\Route;
use CliffordVickrey\MoyersBiggs\Domain\Entity\Questionnaire;
use CliffordVickrey\MoyersBiggs\Domain\Entity\Score;
use CliffordVickrey\MoyersBiggs\Domain\Entity\State;
use CliffordVickrey\MoyersBiggs\Domain\Enum\Valence;
use CliffordVickrey\MoyersBiggs\Domain\Repository\StateRepository;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Domain\Repository\MockQuestionnaireRepository;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Session\MockSession;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\App\Controller\ResultsController
 */
class ResultsControllerTest extends TestCase
{
    private StateRepository $stateRepository;
    private ResultsController $resultsController;
    private MockQuestionnaireRepository $questionnaireRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->stateRepository = new StateRepository(new MockSession());
        $this->resultsController = new ResultsController($this->stateRepository);
        $this->questionnaireRepository = new MockQuestionnaireRepository();

        $state = $this->stateRepository->getState();
        $state[] = 0;
        $state[] = 0;
        $this->stateRepository->saveSate($state);
    }

    /**
     * @return void
     */
    public function testDispatch(): void
    {
        $request = new HttpRequest(Route::ROUTE_RESULTS, [
            Questionnaire::class => $this->questionnaireRepository->getQuestionnaireById(0),
            State::class => $this->stateRepository->getState()
        ]);

        $viewModel = $this->resultsController->dispatch($request);

        self::assertEquals('results', $viewModel->getPartial());

        /** @var Score $score */
        $score = $viewModel->getParams()['score'];

        self::assertEquals(1.0, $score->getPercentage());
        self::assertEquals(Valence::MOYERS, (string)$score->getValence());
    }
}
