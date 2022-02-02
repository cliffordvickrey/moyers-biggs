<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Domain\Enum;

use CliffordVickrey\MoyersBiggs\Domain\Enum\Valence;
use CliffordVickrey\MoyersBiggs\Domain\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

use function json_encode;

class ValenceTest extends TestCase
{
    /**
     * @return void
     */
    public function testConstruct(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectErrorMessage('Invalid value for CliffordVickrey\MoyersBiggs\Domain\Enum\Valence, "something"');
        new Valence('something');
    }

    /**
     * @return void
     */
    public function testToString(): void
    {
        $valence = new Valence(Valence::MOYERS);
        self::assertEquals(Valence::MOYERS, (string)$valence);
    }

    /**
     * @return void
     */
    public function testJsonSerialize(): void
    {
        $valence = new Valence(Valence::MOYERS);
        self::assertEquals('"Moyers"', json_encode($valence));
    }

    /**
     * @return void
     */
    public function testToType(): void
    {
        $valence = new Valence(Valence::MOYERS);
        self::assertEquals('M', $valence->toType());
    }
}
