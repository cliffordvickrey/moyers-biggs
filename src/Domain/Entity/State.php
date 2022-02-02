<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Domain\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

use function array_map;
use function is_array;
use function is_numeric;
use function is_string;

/**
 * Object-oriented API for the user session
 *
 * @extends Collection<int>
 */
final class State extends Collection
{
    private bool $isNew = true;
    /** @var int<0, max> */
    private int $questionnaireId;
    private DateTimeImmutable $startTime;

    /**
     * @param int<0, max> $questionnaireId
     * @param DateTimeImmutable $startTime
     * @param int ...$answerIds
     */
    #[Pure]
    public function __construct(int $questionnaireId, DateTimeImmutable $startTime, int ...$answerIds)
    {
        $this->startTime = $startTime;
        $this->questionnaireId = $questionnaireId;
        parent::__construct(...$answerIds);
    }

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->isNew;
    }

    /**
     * @param bool $isNew
     */
    public function setIsNew(bool $isNew): void
    {
        $this->isNew = $isNew;
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
     * @return array<string, mixed>
     */
    #[ArrayShape(['questionnaireId' => "int", 'startTime' => "string", 'values' => "mixed"])]
    public function __serialize(): array
    {
        return [
            'questionnaireId' => $this->questionnaireId,
            'startTime' => $this->startTime->format(DateTimeInterface::RFC3339),
            'values' => $this->values
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @return void
     */
    public function __unserialize(array $data): void
    {
        $this->isNew = false;

        $questionnaireId = $data['questionnaireId'] ?? 0;

        if (!is_numeric($questionnaireId)) {
            // @codeCoverageIgnoreStart
            $questionnaireId = 0;
            // @codeCoverageIgnoreEnd
        } else {
            $questionnaireId = (int)$questionnaireId;
        }

        if ($questionnaireId < 0) {
            // @codeCoverageIgnoreStart
            $questionnaireId = 0;
            // @codeCoverageIgnoreEnd
        }

        $this->questionnaireId = $questionnaireId;

        $startTime = $data['startTime'] ?? '';

        if (!is_string($startTime)) {
            // @codeCoverageIgnoreStart
            $startTime = '';
            // @codeCoverageIgnoreEnd
        }

        $startTime = DateTimeImmutable::createFromFormat(DateTimeInterface::RFC3339, $startTime);

        if (false === $startTime) {
            // @codeCoverageIgnoreStart
            $startTime = new DateTimeImmutable();
            // @codeCoverageIgnoreEnd
        }

        $this->startTime = $startTime;

        $values = $data['values'] ?? [];

        if (!is_array($values)) {
            // @codeCoverageIgnoreStart
            $values = [];
            // @codeCoverageIgnoreEnd
        }

        $this->values = array_map('\\intval', $values);
    }
}
