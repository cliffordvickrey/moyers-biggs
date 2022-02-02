<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\LogController;
use CliffordVickrey\MoyersBiggs\App\Route\Route;
use CliffordVickrey\MoyersBiggs\Domain\Entity\Event;
use CliffordVickrey\MoyersBiggs\Domain\Entity\EventCollection;
use CliffordVickrey\MoyersBiggs\Domain\Repository\EventRepository;
use CliffordVickrey\MoyersBiggs\Infrastructure\Clock\Clock;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Domain\Repository\MockQuestionnaireRepository;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Io\MockIo;
use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\App\Controller\LogController
 */
class LogControllerTest extends TestCase
{
    private LogController $logController;

    /**
     * @return void
     */
    public function setUp(): void
    {
        /** @var DateTimeImmutable $dateTime */
        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d', '2022-01-01');

        $eventRepository = new EventRepository(new Clock($dateTime), new MockIo());

        for ($i = 5; $i >= 0; $i--) {
            $eventRepository->save(new Event("$i", 0, $dateTime, $dateTime->add(new DateInterval('P1D'))));
        }

        $this->logController = new LogController($eventRepository, new MockQuestionnaireRepository(), 2);
    }

    /**
     * @return void
     */
    public function testDispatch(): void
    {
        $viewModel = $this->logController->dispatch(new HttpRequest(Route::ROUTE_LOG));

        self::assertEquals('log', $viewModel->getPartial());

        $params = $viewModel->getParams();

        self::assertTrue($params['js']);
        self::assertEquals(1, $params['page']);
        self::assertEquals(3, $params['pageCount']);

        /** @var EventCollection $events */
        $events = $params['events'];
        self::assertInstanceOf(EventCollection::class, $events);
        self::assertCount(2, $events);
        self::assertEquals('0', $events[0]->getId());
        self::assertEquals('1', $events[1]->getId());
    }

    /**
     * @return void
     */
    public function testDispatchWithPage(): void
    {
        $viewModel = $this->logController->dispatch(new HttpRequest(Route::ROUTE_LOG, [
            Route::ATTRIBUTE_PAGE => 2
        ]));

        self::assertEquals('log', $viewModel->getPartial());

        $params = $viewModel->getParams();

        self::assertTrue($params['js']);
        self::assertEquals(2, $params['page']);
        self::assertEquals(3, $params['pageCount']);

        /** @var EventCollection $events */
        $events = $params['events'];
        self::assertInstanceOf(EventCollection::class, $events);
        self::assertCount(2, $events);
        self::assertEquals('2', $events[0]->getId());
        self::assertEquals('3', $events[1]->getId());
    }
}
