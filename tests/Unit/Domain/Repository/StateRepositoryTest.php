<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Domain\Repository;

use CliffordVickrey\MoyersBiggs\Domain\Repository\StateRepository;
use CliffordVickrey\MoyersBiggs\Infrastructure\Clock\Clock;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Session\MockSession;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

use function serialize;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Domain\Repository\StateRepository
 */
class StateRepositoryTest extends TestCase
{
    private MockSession $session;
    private StateRepository $stateRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->session = new MockSession();

        /** @var DateTimeImmutable $dateTime */
        $dateTime = DateTimeImmutable::createFromFormat(DateTimeInterface::RFC3339, '2022-01-01T05:24:49+00:00');
        $this->stateRepository = new StateRepository($this->session, new Clock($dateTime));
    }

    /**
     * @return void
     */
    public function testGetState(): void
    {
        $state = $this->stateRepository->getState();
        self::assertEquals('2022-01-01', $state->getStartTime()->format('Y-m-d'));
        self::assertTrue($state->isNew());

        $state[] = 0;
        $this->stateRepository->saveSate($state);

        $state = $this->stateRepository->getState();
        self::assertEquals('2022-01-01', $state->getStartTime()->format('Y-m-d'));
        self::assertEquals([0], $state->toArray());
        self::assertFalse($state->isNew());
    }

    /**
     * @return void
     */
    public function testGetStateInvalid(): void
    {
        $this->session->save(serialize(new stdClass()));
        $state = $this->stateRepository->getState();
        self::assertTrue($state->isNew());
    }

    /**
     * @return void
     */
    public function testDeleteState(): void
    {
        $state = $this->stateRepository->getState();
        self::assertTrue($state->isNew());
        $this->stateRepository->deleteState();
        $state = $this->stateRepository->getState();
        self::assertTrue($state->isNew());
    }
}
