<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Domain\Entity;

use ArrayIterator;
use CliffordVickrey\MoyersBiggs\Domain\Entity\Questionnaire;
use CliffordVickrey\MoyersBiggs\Domain\Enum\Valence;
use CliffordVickrey\MoyersBiggs\Domain\Exception\OutOfBoundException;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Domain\Repository\MockQuestionnaireRepository;
use PHPUnit\Framework\TestCase;

use function json_encode;
use function round;

use const JSON_PRETTY_PRINT;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Domain\Entity\Questionnaire
 */
class QuestionnaireTest extends TestCase
{
    private Questionnaire $questionnaire;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->questionnaire = (new MockQuestionnaireRepository())->getQuestionnaireById(0);
    }

    /**
     * @return void
     */
    public function testGetId(): void
    {
        $this->questionnaire->setId(1);
        self::assertEquals(1, $this->questionnaire->getId());
    }

    /**
     * @return void
     */
    public function testAddFrequencies(): void
    {
        $this->questionnaire->addFrequencies([1, 1]);
        self::assertEquals(1, $this->questionnaire->getFrequency());
    }

    /**
     * @return void
     */
    public function testGetScore(): void
    {
        $score = $this->questionnaire->getScore(new ArrayIterator([0, 0]));
        self::assertEquals(1.0, $score->getPercentage());
        self::assertEquals(Valence::MOYERS, (string)$score->getValence());

        $score = $this->questionnaire->getScore([]);
        self::assertEquals(0.0, $score->getPercentage());
        self::assertEquals(Valence::BIGGS, (string)$score->getValence());
    }

    /**
     * @return void
     */
    public function testGetScoreInvalidQuestion(): void
    {
        $this->expectException(OutOfBoundException::class);
        $this->expectErrorMessage('Invalid question ID, "2"');
        $this->questionnaire->getScore([0, 0, 0]);
    }

    /**
     * @return void
     */
    public function testGetScoreInvalidAnswerId(): void
    {
        $this->expectException(OutOfBoundException::class);
        $this->expectErrorMessage('Invalid answer ID, "2"');
        $this->questionnaire->getScore([0, 2]);
    }

    /**
     * @return void
     */
    public function testGetAverageScore(): void
    {
        $this->questionnaire->addFrequencies([0, 0]);
        $this->questionnaire->addFrequencies([0, 0]);
        $this->questionnaire->addFrequencies([1, 1]);

        $score = $this->questionnaire->getAverageScore();
        $percentage = round($score->getPercentage(), 2);
        self::assertEquals(0.67, $percentage);
        self::assertEquals(Valence::MOYERS, (string)$score->getValence());

        $blankQuestionnaire = new Questionnaire(0);
        $score = $blankQuestionnaire->getAverageScore();
        self::assertEquals(0.0, $score->getPercentage());
        self::assertEquals(Valence::BIGGS, (string)$score->getValence());
    }

    /**
     * @return void
     */
    public function testJsonSerialize(): void
    {
        $this->questionnaire->addFrequencies([0, 0]);
        $this->questionnaire->addFrequencies([0, 0]);
        $this->questionnaire->addFrequencies([1, 1]);

        $json = <<< JSON
[
    [
        2,
        1
    ],
    [
        2,
        1
    ]
]
JSON;

        self::assertEquals($json, json_encode($this->questionnaire, JSON_PRETTY_PRINT));
    }
}
