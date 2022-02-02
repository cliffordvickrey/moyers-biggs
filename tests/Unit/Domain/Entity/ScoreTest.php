<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Domain\Entity;

use CliffordVickrey\MoyersBiggs\Domain\Entity\Score;
use CliffordVickrey\MoyersBiggs\Domain\Enum\Valence;
use PHPUnit\Framework\TestCase;

use function json_encode;

use const JSON_PRETTY_PRINT;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Domain\Entity\Score
 */
class ScoreTest extends TestCase
{
    private Score $score;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->score = new Score(0.75, new Valence(Valence::BIGGS));
    }

    /**
     * @return void
     */
    public function testGetPercentage(): void
    {
        $this->score->setPercentage(0.60);
        self::assertEquals(0.60, $this->score->getPercentage());
    }

    /**
     * @return void
     */
    public function testGetValence(): void
    {
        $this->score->setValence(new Valence(Valence::MOYERS));
        self::assertEquals(Valence::MOYERS, (string)$this->score->getValence());
    }

    /**
     * @return void
     */
    public function testJsonSerialize(): void
    {
        $json = <<< JSON
{
    "percentage": 0.75,
    "valence": "Biggs"
}
JSON;

        self::assertEquals($json, json_encode($this->score, JSON_PRETTY_PRINT));
    }
}
