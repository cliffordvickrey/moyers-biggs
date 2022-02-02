<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Domain\Entity;

use CliffordVickrey\MoyersBiggs\Domain\Enum\Valence;
use CliffordVickrey\MoyersBiggs\Domain\Exception\OutOfBoundException;
use JetBrains\PhpStorm\Pure;
use Traversable;

use function array_combine;
use function array_keys;
use function array_map;
use function array_reduce;
use function array_sum;
use function arsort;
use function count;
use function iterator_to_array;
use function max;
use function round;
use function sprintf;

use const SORT_NUMERIC;

/**
 * The questionnaire entity
 *
 * @extends Collection<Question>
 */
final class Questionnaire extends Collection
{
    /** @var int<0, max> */
    private int $id;

    /**
     * @param int<0, max> $id
     * @param Question ...$values
     */
    #[Pure]
    public function __construct(int $id = 0, Question ...$values)
    {
        $this->id = $id;
        parent::__construct(...$values);
    }

    /**
     * @return int<0, max>
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int<0, max> $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param list<int> $answerIds
     * @return void
     */
    public function addFrequencies(iterable $answerIds): void
    {
        foreach ($answerIds as $questionId => $answerId) {
            $answer = $this->values[$questionId][$answerId];
            $frequency = $answer->getFrequency() + 1;
            $answer->setFrequency(max($frequency, 0));
        }
    }

    /**
     * @param list<int> $answerIds
     * @return Score
     */
    public function getScore(iterable $answerIds): Score
    {
        if ($answerIds instanceof Traversable) {
            $answerIds = iterator_to_array($answerIds);
        }

        if (0 === count($answerIds)) {
            return new Score(0.0, new Valence(Valence::BIGGS));
        }

        $sumsByValence = $this->sumByValence($answerIds);

        $sumOfSums = array_sum($sumsByValence);

        /** @var non-empty-array<string, float> $percentagesByValence */
        $percentagesByValence = array_combine(
            array_keys($sumsByValence),
            array_map(fn(int $sum) => round($sum / $sumOfSums, 2), $sumsByValence)
        );

        arsort($percentagesByValence, SORT_NUMERIC);
        $firstKey = array_key_first($percentagesByValence);
        return new Score($percentagesByValence[$firstKey], new Valence($firstKey));
    }

    /**
     * @param array<int> $answerIds
     * @return array<string, int>
     */
    private function sumByValence(array $answerIds): array
    {
        $sumByValence = array_reduce($answerIds, function (array $carry, int $item): array {
            $max = ++$carry['__max'];

            if (!isset($this->values[$max])) {
                throw new OutOfBoundException(sprintf('Invalid question ID, "%d"', $max));
            }

            $question = $this->values[$max];

            if (!$question->offsetExists($item)) {
                throw new OutOfBoundException(sprintf('Invalid answer ID, "%d", for question %d', $item, $max));
            }

            $answer = $question[$item];
            $key = (string)$answer->getValence();

            if (!isset($carry[$key])) {
                $carry[$key] = 0;
            }

            $carry[$key] += $answer->getIntensity();
            return $carry;
        }, ['__max' => -1]);

        unset($sumByValence['__max']);

        return $sumByValence;
    }

    /**
     * @return Score
     */
    public function getAverageScore(): Score
    {
        $sumsByValence = [];

        if (0 === count($this->values)) {
            return new Score(0.0, new Valence(Valence::BIGGS));
        }

        foreach ($this->values as $question) {
            foreach ($question as $answer) {
                $valence = (string)$answer->getValence();

                if (!isset($sumsByValence[$valence])) {
                    $sumsByValence[$valence] = 0;
                }

                $sumsByValence[$valence] += $answer->getIntensity() * $answer->getFrequency();
            }
        }

        $sumOfSums = array_sum($sumsByValence);

        /** @var non-empty-array<string, float> $percentagesByValence */
        $percentagesByValence = array_combine(
            array_keys($sumsByValence),
            array_map(fn(int $sum) => round($sum / $sumOfSums, 2), $sumsByValence)
        );

        arsort($percentagesByValence, SORT_NUMERIC);
        $firstKey = array_key_first($percentagesByValence);
        return new Score($percentagesByValence[$firstKey], new Valence($firstKey));
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $frequencies = [];

        foreach ($this->values as $i => $question) {
            $frequencies[$i] = [];

            foreach ($question as $ii => $answer) {
                $frequencies[$i][$ii] = $answer->getFrequency();
            }
        }

        return $frequencies;
    }

    /**
     * @return int
     */
    public function getFrequency(): int
    {
        return array_sum(array_map(fn($question): int => $question->getFrequency(), $this->values))
            / (count($this->values) ?: 1);
    }
}
