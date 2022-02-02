<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Domain\Repository;

use CliffordVickrey\MoyersBiggs\Domain\Entity\Event;
use CliffordVickrey\MoyersBiggs\Domain\Repository\EventRepository;
use CliffordVickrey\MoyersBiggs\Infrastructure\Clock\Clock;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Io\MockIo;
use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;

use function json_encode;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Domain\Repository\EventRepository
 * @covers \CliffordVickrey\MoyersBiggs\Domain\Entity\EventCollection
 */
class EventRepositoryTest extends TestCase
{
    private EventRepository $eventRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        /** @var DateTimeImmutable $dateTime */
        $dateTime = DateTimeImmutable::createFromFormat(DateTimeInterface::RFC3339, '2022-01-01T05:24:49+00:00');
        $this->eventRepository = new EventRepository(new Clock($dateTime), new MockIo());
    }

    /**
     * @return void
     */
    public function testSave(): void
    {
        /** @var DateTimeImmutable $dateTime */
        $dateTime = DateTimeImmutable::createFromFormat(DateTimeInterface::RFC3339, '2022-01-01T05:24:49+00:00');
        $event = new Event('something', 0, $dateTime, $dateTime->add(new DateInterval('P1D')), 0, 1);
        $this->eventRepository->save($event);
        self::assertEquals(1, $this->eventRepository->getCount(0));
        $events = $this->eventRepository->getEvents(0);
        self::assertEquals(json_encode($event), json_encode($events[0]));
    }

    /**
     * @return void
     */
    public function testGetEvents(): void
    {
        /** @var DateTimeImmutable $dateTime */
        $dateTime = DateTimeImmutable::createFromFormat(DateTimeInterface::RFC3339, '2022-01-01T05:24:49+00:00');
        $event = new Event('something', 0, $dateTime, $dateTime->add(new DateInterval('P1D')), 0, 1);
        $this->eventRepository->save($event);
        $secondEvent = new Event('something else', 0, $dateTime, $dateTime->add(new DateInterval('P1D')), 0, 1);
        $this->eventRepository->save($secondEvent);
        $events = $this->eventRepository->getEvents(0, 1, 1);
        self::assertEquals(json_encode($event), json_encode($events[0]));
    }

    /**
     * @return void
     */
    public function testGetCount(): void
    {
        self::assertEquals(0, $this->eventRepository->getCount(0));
    }
}
