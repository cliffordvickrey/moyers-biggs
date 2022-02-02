<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Domain\Entity;

use CliffordVickrey\MoyersBiggs\Domain\Entity\Collection;
use CliffordVickrey\MoyersBiggs\Domain\Entity\Event;
use CliffordVickrey\MoyersBiggs\Domain\Exception\OutOfBoundException;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

use function iterator_to_array;
use function json_encode;

use const JSON_PRETTY_PRINT;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Domain\Entity\Event
 * @covers \CliffordVickrey\MoyersBiggs\Domain\Entity\Collection
 */
class EventTest extends TestCase
{
    private Event $event;

    /**
     * @return void
     */
    public function setUp(): void
    {
        /** @var DateTimeImmutable $startTime */
        $startTime = DateTimeImmutable::createFromFormat(DateTimeInterface::RFC3339, '2022-01-01T05:24:49+00:00');
        /** @var DateTimeImmutable $stopTime */
        $stopTime = DateTimeImmutable::createFromFormat(DateTimeInterface::RFC3339, '2022-01-02T05:24:49+00:00');

        $this->event = new Event('something', 0, $startTime, $stopTime);
    }

    /**
     * @return void
     */
    public function testSetId(): void
    {
        $this->event->setId('something else');
        self::assertEquals('something else', $this->event->getId());
    }

    /**
     * @return void
     */
    public function testSetQuestionnaireId(): void
    {
        $this->event->setQuestionnaireId(1);
        self::assertEquals(1, $this->event->getQuestionnaireId());
    }

    /**
     * @return void
     */
    public function testSetStartTime(): void
    {
        /** @var DateTimeImmutable $dateTime */
        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d', '2000-03-30');
        $this->event->setStartTime($dateTime);
        self::assertEquals('2000-03-30', $this->event->getStartTime()->format('Y-m-d'));
    }

    /**
     * @return void
     */
    public function testSetStopTime(): void
    {
        /** @var DateTimeImmutable $dateTime */
        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d', '2001-04-20');
        $this->event->setStopTime($dateTime);
        self::assertEquals('2001-04-20', $this->event->getStopTime()->format('Y-m-d'));
    }

    /**
     * @return void
     */
    public function testJsonSerialize(): void
    {
        $json = <<<JSON
{
    "id": "something",
    "questionnaireId": 0,
    "startTime": "2022-01-01T05:24:49+00:00",
    "stopTime": "2022-01-02T05:24:49+00:00",
    "answerIds": []
}
JSON;

        self::assertEquals($json, json_encode($this->event, JSON_PRETTY_PRINT));

        /**
         * @extends Collection<int>
         */
        $collection = new class extends Collection {
        };

        $collection[] = 0;

        self::assertEquals('[0]', json_encode($collection));
    }

    /**
     * @return void
     */
    public function testClone(): void
    {
        $this->event[] = 1;
        $clone = clone $this->event;
        self::assertEquals(json_encode($this->event), json_encode($clone));

        /**
         * @extends Collection<stdClass>
         */
        $a = new class extends Collection {
        };

        $obj = new stdClass();
        $obj->foo = 'bar';

        $a[] = $obj;
        $b = clone $a;

        self::assertEquals('bar', $b[0]->foo);
    }

    /**
     * @return void
     */
    public function testCount(): void
    {
        $this->event[] = 1;
        self::assertCount(1, $this->event);
    }

    /**
     * @return void
     */
    public function testGetIterator(): void
    {
        $this->event[] = 0;
        $this->event[] = 1;
        $iterator = $this->event->getIterator();
        $array = iterator_to_array($iterator);

        self::assertEquals([0, 1], $array);
    }

    /**
     * @return void
     */
    public function testOffsetExists(): void
    {
        $this->event[] = 0;
        self::assertTrue(isset($this->event[0]));
        self::assertTrue(isset($this->event[new stdClass()])); // cast to 0
    }

    /**
     * @return void
     */
    public function testSlice(): void
    {
        $this->event[] = 0;
        $this->event[] = 1;
        $this->event[] = 2;
        $this->event->slice(1, 1);
        self::assertEquals([1], $this->event->toArray());
    }

    /**
     * @return void
     */
    public function testMerge(): void
    {
        $this->event[] = 0;
        $this->event->merge([1, 2]);
        self::assertEquals([0, 1, 2], $this->event->toArray());
    }

    /**
     * @return void
     */
    public function testOffsetGet(): void
    {
        $this->expectException(OutOfBoundException::class);
        /** @phpstan-ignore-next-line */
        $this->event[0];
    }

    /**
     * @return void
     */
    public function testOffsetSet(): void
    {
        $this->event[] = 0;
        $this->event[0] = 1;
        self::assertEquals([1], $this->event->toArray());
    }

    /**
     * @return void
     */
    public function testOffsetUnset(): void
    {
        $this->event[] = 0;
        unset($this->event[0]);
        self::assertCount(0, $this->event);
    }

    /**
     * @return void
     */
    public function testUnshift(): void
    {
        $this->event[] = 0;
        $this->event->unshift(1);
        self::assertEquals([1, 0], $this->event->toArray());
    }
}
