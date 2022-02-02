<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Domain\Repository;

use CliffordVickrey\MoyersBiggs\Domain\Enum\Valence;
use CliffordVickrey\MoyersBiggs\Domain\Exception\OutOfBoundException;
use CliffordVickrey\MoyersBiggs\Domain\Exception\UnexpectedValueException;
use CliffordVickrey\MoyersBiggs\Domain\Repository\QuestionnaireRepository;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Io\MockIo;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Domain\Repository\QuestionnaireRepository
 */
class QuestionnaireRepositoryTest extends TestCase
{
    private QuestionnaireRepository $questionnaireRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $data = [[
            [
                'text' => 'Question 1',
                'answers' => [
                    [
                        'text' => 'Moyers',
                        'valence' => Valence::MOYERS
                    ],
                    [
                        'text' => 'Biggs',
                        'valence' => Valence::BIGGS
                    ]
                ]
            ],
            [
                'text' => 'Question 2',
                'answers' => [
                    [
                        'text' => 'Moyers',
                        'valence' => Valence::MOYERS
                    ],
                    [
                        'text' => 'Biggs',
                        'valence' => Valence::BIGGS
                    ]
                ]
            ]
        ], new stdClass()];

        $this->questionnaireRepository = new QuestionnaireRepository($data, new MockIo());
    }

    /**
     * @return void
     */
    public function testGetQuestionnaireById(): void
    {
        $questionnaire = $this->questionnaireRepository->getQuestionnaireById(0);
        self::assertCount(2, $questionnaire);
        $questionnaire = $this->questionnaireRepository->getQuestionnaireById(0, true);
        self::assertCount(2, $questionnaire);
        self::assertEquals(0, $questionnaire->getFrequency());
        $questionnaire->addFrequencies([0, 0]);
        $this->questionnaireRepository->saveQuestionnaireFrequencies($questionnaire);
        $questionnaire = $this->questionnaireRepository->getQuestionnaireById(0, true);
        self::assertEquals(1, $questionnaire->getFrequency());
    }

    /**
     * @return void
     */
    public function testGetQuestionnaireByIdOutOfBounds(): void
    {
        $this->expectException(OutOfBoundException::class);
        $this->expectErrorMessage('Questionnaire with id "2" not found');
        $this->questionnaireRepository->getQuestionnaireById(2);
    }

    /**
     * @return void
     */
    public function testGetQuestionnaireByIdInvalid(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectErrorMessage('Expected array for questionnaire config; got object');
        $this->questionnaireRepository->getQuestionnaireById(1);
    }
}
