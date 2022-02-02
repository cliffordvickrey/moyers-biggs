<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Domain\Entity;

use CliffordVickrey\MoyersBiggs\Domain\Entity\State;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

use function serialize;
use function unserialize;

class StateTest extends TestCase
{
    private State $state;

    /**
     * @return void
     */
    public function setUp(): void
    {
        /** @var DateTimeImmutable $dateTime */
        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d', '2022-01-01');
        $this->state = new State(0, $dateTime);
    }

    /**
     * @return void
     */
    public function testIsNew(): void
    {
        self::assertTrue($this->state->isNew());
        $this->state->setIsNew(false);
        self::assertFalse($this->state->isNew());
    }

    /**
     * @return void
     */
    public function testGetQuestionnaireId(): void
    {
        $this->state->setQuestionnaireId(1);
        self::assertEquals(1, $this->state->getQuestionnaireId());
    }

    /**
     * @return void
     */
    public function testGetStartTime(): void
    {
        /** @var DateTimeImmutable $dateTime */
        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d', '2000-01-01');
        $this->state->setStartTime($dateTime);
        self::assertEquals('2000-01-01', $this->state->getStartTime()->format('Y-m-d'));
    }

    /**
     * @return void
     */
    public function testSerialize(): void
    {
        $this->state[] = 0;
        /** @var State $object */
        $object = unserialize(serialize($this->state), ['allowed_classes' => [State::class]]);
        self::assertInstanceOf(State::class, $object);
        self::assertEquals('2022-01-01', $object->getStartTime()->format('Y-m-d'));
        self::assertEquals(0, $object->getQuestionnaireId());
        self::assertFalse($object->isNew());
        self::assertEquals(0, $object[0]);
    }
}
