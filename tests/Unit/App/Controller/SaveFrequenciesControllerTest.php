<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\SaveFrequenciesController;
use CliffordVickrey\MoyersBiggs\App\Route\Route;
use CliffordVickrey\MoyersBiggs\Domain\Entity\State;
use CliffordVickrey\MoyersBiggs\Domain\Repository\StateRepository;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Domain\Repository\MockQuestionnaireRepository;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Session\MockSession;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\App\Controller\SaveFrequenciesController
 */
class SaveFrequenciesControllerTest extends TestCase
{
    private SaveFrequenciesController $saveFrequenciesController;
    private MockQuestionnaireRepository $questionnaireRepository;
    private StateRepository $stateRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->questionnaireRepository = new MockQuestionnaireRepository();
        $this->saveFrequenciesController = new SaveFrequenciesController($this->questionnaireRepository);
        $this->stateRepository = new StateRepository(new MockSession());

        $state = $this->stateRepository->getState();
        $state[] = 0;
        $state[] = 1;
        $this->stateRepository->saveSate($state);
    }

    /**
     * @return void
     */
    public function testDispatch(): void
    {
        $state = $this->stateRepository->getState();

        $request = new HttpRequest(Route::ROUTE_SAVE_FREQUENCIES, [State::class => $state]);
        $viewModel = $this->saveFrequenciesController->dispatch($request);

        self::assertEquals(Route::ROUTE_RESULTS, $viewModel->getRouteTo());

        $questionnaire = $this->questionnaireRepository->getQuestionnaireById(0);

        self::assertEquals(1, $questionnaire->getFrequency());
    }
}
