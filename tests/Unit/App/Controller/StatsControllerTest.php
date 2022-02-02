<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\StatsController;
use CliffordVickrey\MoyersBiggs\App\Route\Route;
use CliffordVickrey\MoyersBiggs\Domain\Entity\Questionnaire;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Domain\Repository\MockQuestionnaireRepository;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\App\Controller\StatsController
 */
class StatsControllerTest extends TestCase
{
    private MockQuestionnaireRepository $questionnaireRepository;
    private StatsController $statsController;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->questionnaireRepository = new MockQuestionnaireRepository();
        $this->statsController = new StatsController($this->questionnaireRepository);
    }

    /**
     * @return void
     */
    public function testDispatch(): void
    {
        $questionnaire = $this->questionnaireRepository->getQuestionnaireById(0);
        $questionnaire->addFrequencies([0, 0]);
        $this->questionnaireRepository->saveQuestionnaireFrequencies($questionnaire);

        $request = new HttpRequest(Route::ROUTE_STATS);

        $viewModel = $this->statsController->dispatch($request);

        $params = $viewModel->getParams();

        self::assertEquals('stats', $viewModel->getPartial());
        self::assertEquals(true, $params['js']);

        /** @var Questionnaire $questionnaire */
        $questionnaire = $params['questionnaire'];

        self::assertInstanceOf(Questionnaire::class, $questionnaire);
        self::assertEquals(1, $questionnaire->getFrequency());
    }
}
