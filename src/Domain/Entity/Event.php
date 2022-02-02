<?php

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Domain\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;

/**
 * Event to log
 *
 * @extends Collection<int>
 */
final class Event extends Collection implements JsonSerializable
{
    private string $id;
    /** @var int<0, max> */
    private int $questionnaireId;
    private DateTimeImmutable $startTime;
    private DateTimeImmutable $stopTime;

    /**
     * @param string $id
     * @param int<0, max> $questionnaireId
     * @param DateTimeImmutable $startTime
     * @param DateTimeImmutable $stopTime
     * @param int ...$answerIds
     */
    #[Pure]
    public function __construct(
        string $id,
        int $questionnaireId,
        DateTimeImmutable $startTime,
        DateTimeImmutable $stopTime,
        int ...$answerIds
    ) {
        $this->id = $id;
        $this->questionnaireId = $questionnaireId;
        $this->startTime = $startTime;
        $this->stopTime = $stopTime;
        parent::__construct(...$answerIds);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int<0, max>
     */
    public function getQuestionnaireId(): int
    {
        return $this->questionnaireId;
    }

    /**
     * @param int<0, max> $questionnaireId
     */
    public function setQuestionnaireId(int $questionnaireId): void
    {
        $this->questionnaireId = $questionnaireId;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getStartTime(): DateTimeImmutable
    {
        return $this->startTime;
    }

    /**
     * @param DateTimeImmutable $startTime
     */
    public function setStartTime(DateTimeImmutable $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getStopTime(): DateTimeImmutable
    {
        return $this->stopTime;
    }

    /**
     * @param DateTimeImmutable $stopTime
     */
    public function setStopTime(DateTimeImmutable $stopTime): void
    {
        $this->stopTime = $stopTime;
    }

    /**
     * @inheritDoc
     */
    #[ArrayShape([
        'id' => "string",
        'questionnaireId' => "int",
        'startTime' => "string",
        'stopTime' => "string",
        'answerIds' => "mixed"
    ])]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'questionnaireId' => $this->questionnaireId,
            'startTime' => $this->startTime->format(DateTimeInterface::RFC3339),
            'stopTime' => $this->stopTime->format(DateTimeInterface::RFC3339),
            'answerIds' => $this->values
        ];
    }
}
