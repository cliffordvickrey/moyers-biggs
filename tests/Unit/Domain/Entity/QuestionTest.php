<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Domain\Entity;

use CliffordVickrey\MoyersBiggs\Domain\Entity\Answer;
use CliffordVickrey\MoyersBiggs\Domain\Entity\Question;
use CliffordVickrey\MoyersBiggs\Domain\Enum\Valence;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Domain\Entity\Question
 */
class QuestionTest extends TestCase
{
    private Question $question;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->question = new Question('some question');
        $this->question[] = new Answer('answer 1', new Valence(Valence::MOYERS), 1, 5);
        $this->question[] = new Answer('answer 2', new Valence(Valence::BIGGS), 1, 10);
    }

    /**
     * @return void
     */
    public function testGetText(): void
    {
        $this->question->setText('some other question');
        self::assertEquals('some other question', $this->question->getText());
    }

    /**
     * @return void
     */
    public function testGetFrequency(): void
    {
        self::assertEquals(15, $this->question->getFrequency());
    }
}
