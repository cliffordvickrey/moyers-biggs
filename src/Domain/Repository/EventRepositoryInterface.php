<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Domain\Repository;

use CliffordVickrey\MoyersBiggs\Domain\Entity\Event;
use CliffordVickrey\MoyersBiggs\Domain\Entity\EventCollection;

interface EventRepositoryInterface
{
    /**
     * Log an event
     * @param Event $event
     * @return void
     */
    public function save(Event $event): void;

    /**
     * Fetch a slice of events
     * @param int<0, max> $questionnaireId
     * @param int<0, max> $offset
     * @param positive-int|null $length
     * @return EventCollection
     */
    public function getEvents(int $questionnaireId, int $offset = 0, ?int $length = null): EventCollection;

    /**
     * Fetch the total number of logged events
     * @param int<0, max> $questionnaireId
     * @return int<0, max>
     */
    public function getCount(int $questionnaireId): int;
}
