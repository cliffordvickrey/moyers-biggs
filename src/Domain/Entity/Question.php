<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Domain\Entity;

use JetBrains\PhpStorm\Pure;

use function array_map;
use function array_sum;

/**
 * The question entity
 *
 * @extends Collection<Answer>
 */
final class Question extends Collection
{
    private string $text;

    /**
     * @param string $text
     * @param Answer ...$answers
     */
    #[Pure]
    public function __construct(string $text, Answer ...$answers)
    {
        $this->text = $text;
        parent::__construct(...$answers);
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getFrequency(): int
    {
        return array_sum(array_map(fn($answer): int => $answer->getFrequency(), $this->values));
    }
}
