<?php

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Domain\Hydrator;

use CliffordVickrey\MoyersBiggs\Domain\Entity\Event;
use CliffordVickrey\MoyersBiggs\Domain\Entity\EventCollection;
use CliffordVickrey\MoyersBiggs\Domain\Exception\UnexpectedValueException;
use DateTimeImmutable;
use DateTimeInterface;

use function is_array;
use function is_numeric;
use function is_scalar;
use function is_string;

/**
 * Converts an event to an array and vice-versa
 */
final class EventHydrator
{
    /**
     * @param array<mixed> $data
     * @return EventCollection
     */
    public function hydrateMultiple(array $data): EventCollection
    {
        $collection = new EventCollection();

        foreach ($data as $row) {
            if (!is_array($row)) {
                throw new UnexpectedValueException('Expected array of arrays');
            }

            $collection[] = $this->hydrate($row);
        }

        return $collection;
    }

    /**
     * @param array<mixed> $data
     * @return Event
     */
    public function hydrate(array $data): Event
    {
        $id = $data['id'] ?? null;

        if (is_scalar($id)) {
            $id = (string)$id;
        } else {
            $id = '';
        }

        $questionnaireId = $data['questionnaireId'] ?? null;

        if (!is_numeric($questionnaireId)) {
            $questionnaireId = 0;
        } else {
            $questionnaireId = (int)$questionnaireId;
        }

        if ($questionnaireId < 0) {
            $questionnaireId = 0;
        }

        $startTime = $data['startTime'] ?? null;

        if (!is_string($startTime)) {
            throw new UnexpectedValueException('Expected "startTime" to be a string');
        }

        $stopTime = $data['stopTime'] ?? null;

        if (!is_string($stopTime)) {
            throw new UnexpectedValueException('Expected "stopTime" to be a string');
        }

        $answerIds = $data['answerIds'] ?? null;

        if (!is_array($answerIds)) {
            throw new UnexpectedValueException('Expected "answerIds" to be an array');
        }

        return new Event(
            $id,
            $questionnaireId,
            DateTimeImmutable::createFromFormat(DateTimeInterface::RFC3339, $startTime) ?: new DateTimeImmutable(),
            DateTimeImmutable::createFromFormat(DateTimeInterface::RFC3339, $stopTime) ?: new DateTimeImmutable(),
            ...$answerIds
        );
    }
}
