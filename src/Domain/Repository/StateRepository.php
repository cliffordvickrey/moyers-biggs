<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Domain\Repository;

use CliffordVickrey\MoyersBiggs\Domain\Entity\State;
use CliffordVickrey\MoyersBiggs\Infrastructure\Clock\Clock;
use CliffordVickrey\MoyersBiggs\Infrastructure\Clock\ClockInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Session\SessionInterface;
use JetBrains\PhpStorm\Pure;

use function serialize;
use function unserialize;

class StateRepository implements StateRepositoryInterface
{
    private ClockInterface $clock;
    private SessionInterface $session;

    /**
     * @param SessionInterface $session
     * @param ClockInterface|null $clock
     */
    #[Pure]
    public function __construct(SessionInterface $session, ?ClockInterface $clock = null)
    {
        $this->clock = $clock ?? new Clock();
        $this->session = $session;
    }

    /**
     * @inheritDoc
     */
    public function getState(): State
    {
        $serialized = $this->session->get();

        if (null === $serialized) {
            return $this->getNewState();
        }

        $state = unserialize($serialized, ['allowed_classes' => [State::class]]);

        if ($state instanceof State) {
            return $state;
        }

        return $this->getNewState();
    }

    /**
     * @return State
     */
    private function getNewState(): State
    {
        return new State(0, $this->clock->now());
    }

    /**
     * @return void
     */
    public function deleteState(): void
    {
        $this->session->destroy();
    }

    /**
     * @inheritDoc
     */
    public function saveSate(State $state): void
    {
        $this->session->save(serialize($state));
    }
}
