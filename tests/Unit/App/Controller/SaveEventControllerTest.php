<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\SaveEventController;
use CliffordVickrey\MoyersBiggs\App\Route\Route;
use CliffordVickrey\MoyersBiggs\Domain\Entity\Event;
use CliffordVickrey\MoyersBiggs\Domain\Entity\State;
use CliffordVickrey\MoyersBiggs\Domain\Repository\EventRepository;
use CliffordVickrey\MoyersBiggs\Infrastructure\Clock\Clock;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use CliffordVickrey\MoyersBiggs\Infrastructure\UniqueId\UniqueId;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Io\MockIo;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\App\Controller\SaveEventController
 */
class SaveEventControllerTest extends TestCase
{
    private EventRepository $eventRepository;
    private SaveEventController $saveEventController;

    /**
     * @return void
     */
    public function setUp(): void
    {
        /** @var DateTimeImmutable $dateTime */
        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d', '2021-01-01');
        $clock = new Clock($dateTime);
        $uniqueId = new UniqueId('something');

        $this->eventRepository = new EventRepository($clock, new MockIo());
        $this->saveEventController = new SaveEventController($this->eventRepository, $clock, $uniqueId);
    }

    /**
     * @return void
     */
    public function testDispatch(): void
    {
        /** @var DateTimeImmutable $dateTime */
        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d', '2020-12-30');
        $state = new State(0, $dateTime, 0, 0);
        $request = new HttpRequest(Route::ROUTE_SAVE_EVENT, [State::class => $state]);
        $viewModel = $this->saveEventController->dispatch($request);

        self::assertEquals(Route::ROUTE_SAVE_FREQUENCIES, $viewModel->getRouteTo());

        $events = $this->eventRepository->getEvents(0);
        self::assertCount(1, $events);
        /** @var Event $event */
        $event = $events[0];
        self::assertEquals([0, 0], $event->toArray());
        self::assertEquals('something', $event->getId());
        self::assertEquals(0, $event->getQuestionnaireId());
        self::assertEquals('2020-12-30', $event->getStartTime()->format('Y-m-d'));
        self::assertEquals('2021-01-01', $event->getStopTime()->format('Y-m-d'));
    }
}
