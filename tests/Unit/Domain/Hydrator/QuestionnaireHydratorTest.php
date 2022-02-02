<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Domain\Hydrator;

use CliffordVickrey\MoyersBiggs\Domain\Enum\Valence;
use CliffordVickrey\MoyersBiggs\Domain\Exception\UnexpectedValueException;
use CliffordVickrey\MoyersBiggs\Domain\Hydrator\QuestionnaireHydrator;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Domain\Hydrator\QuestionnaireHydrator
 */
class QuestionnaireHydratorTest extends TestCase
{
    private QuestionnaireHydrator $hydrator;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->hydrator = new QuestionnaireHydrator();
    }

    public function testHydrate(): void
    {
        $data = [
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
        ];

        $questionnaire = $this->hydrator->hydrate($data);
        self::assertCount(2, $questionnaire);
    }

    /**
     * @return void
     */
    public function testHydrateInvalid(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectErrorMessage('Expected array for question config; got object');
        $this->hydrator->hydrate([new stdClass()]);
    }

    /**
     * @return void
     */
    public function testHydrateInvalidQuestionText(): void
    {
        $data = [[
            'text' => new stdClass(),
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
        ]];

        $this->expectException(UnexpectedValueException::class);
        $this->expectErrorMessage('Expected string for question text; got object');
        $this->hydrator->hydrate($data);
    }

    /**
     * @return void
     */
    public function testHydrateInvalidAnswers(): void
    {
        $data = [[
            'text' => 'Question 1',
            'answers' => new stdClass()
        ]];

        $this->expectException(UnexpectedValueException::class);
        $this->expectErrorMessage('Expected array for question answers; got object');
        $this->hydrator->hydrate($data);
    }

    /**
     * @return void
     */
    public function testHydrateInvalidAnswerText(): void
    {
        $data = [[
            'text' => 'Question 1',
            'answers' => [
                [
                    'text' => new stdClass(),
                    'valence' => Valence::MOYERS
                ],
                [
                    'text' => 'Biggs',
                    'valence' => Valence::BIGGS
                ]
            ]
        ]];

        $this->expectException(UnexpectedValueException::class);
        $this->expectErrorMessage('Expected string for answer text; got object');
        $this->hydrator->hydrate($data);
    }

    /**
     * @return void
     */
    public function testHydrateInvalidValence(): void
    {
        $data = [[
            'text' => 'Question 1',
            'answers' => [
                [
                    'text' => 'Moyers',
                    'valence' => new stdClass()
                ],
                [
                    'text' => 'Biggs',
                    'valence' => Valence::BIGGS
                ]
            ]
        ]];

        $this->expectException(UnexpectedValueException::class);
        $this->expectErrorMessage('Expected string or instance of CliffordVickrey\MoyersBiggs\Domain\Enum\Valence for '
            . 'answer valence; got string');
        $this->hydrator->hydrate($data);
    }

    /**
     * @return void
     */
    public function testHydrateInvalidIntensity(): void
    {
        $data = [[
            'text' => 'Question 1',
            'answers' => [
                [
                    'text' => 'Moyers',
                    'valence' => Valence::MOYERS,
                    'intensity' => new stdClass()
                ],
                [
                    'text' => 'Biggs',
                    'valence' => Valence::BIGGS
                ]
            ]
        ]];

        $this->expectException(UnexpectedValueException::class);
        $this->expectErrorMessage('Expected numeric value or NULL for answer intensity');
        $this->hydrator->hydrate($data);
    }

    /**
     * @return void
     */
    public function testHydrateNegativeIntensity(): void
    {
        $data = [[
            'text' => 'Question 1',
            'answers' => [
                [
                    'text' => 'Moyers',
                    'valence' => Valence::MOYERS,
                    'intensity' => -1
                ],
                [
                    'text' => 'Biggs',
                    'valence' => Valence::BIGGS
                ]
            ]
        ]];

        $this->expectException(UnexpectedValueException::class);
        $this->expectErrorMessage('Answer intensity cannot be less than 1');
        $this->hydrator->hydrate($data);
    }

    /**
     * @return void
     */
    public function testHydrateQuestionWithNoAnswers(): void
    {
        $data = [[
            'text' => 'Question 1',
            'answers' => []
        ]];

        $this->expectException(UnexpectedValueException::class);
        $this->expectErrorMessage('Question has no answers associated with it');
        $this->hydrator->hydrate($data);
    }
}
