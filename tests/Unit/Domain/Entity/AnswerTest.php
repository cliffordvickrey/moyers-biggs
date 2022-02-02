<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Domain\Entity;

use CliffordVickrey\MoyersBiggs\Domain\Entity\Answer;
use CliffordVickrey\MoyersBiggs\Domain\Enum\Valence;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Domain\Entity\Answer
 */
class AnswerTest extends TestCase
{
    private Answer $answer;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->answer = new Answer('answer text', new Valence(Valence::MOYERS), 1, 100);
    }

    /**
     * @return void
     */
    public function testGetText(): void
    {
        $this->answer->setText('new answer text');
        self::assertEquals('new answer text', $this->answer->getText());
    }

    /**
     * @return void
     */
    public function testGetValence(): void
    {
        $this->answer->setValence(new Valence(Valence::BIGGS));
        self::assertEquals(Valence::BIGGS, (string)$this->answer->getValence());
    }

    /**
     * @return void
     */
    public function testGetIntensity(): void
    {
        $this->answer->setIntensity(10);
        self::assertEquals(10, $this->answer->getIntensity());
    }

    /**
     * @return void
     */
    public function testGetFrequency(): void
    {
        $this->answer->setFrequency(1000);
        self::assertEquals(1000, $this->answer->getFrequency());
    }

    /**
     * @return void
     */
    public function testClone(): void
    {
        $answer = clone $this->answer;
        $answer->setValence(new Valence(Valence::BIGGS));
        self::assertEquals(Valence::MOYERS, (string)$this->answer->getValence());
    }
}
