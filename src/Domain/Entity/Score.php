<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Domain\Entity;

use CliffordVickrey\MoyersBiggs\Domain\Enum\Valence;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

/**
 * A user's score (personality type + percentage)
 */
final class Score implements JsonSerializable
{
    private float $percentage;
    private Valence $valence;

    /**
     * @param float $percentage
     * @param Valence $valence
     */
    public function __construct(float $percentage, Valence $valence)
    {
        $this->percentage = $percentage;
        $this->valence = $valence;
    }

    /**
     * @return float
     */
    public function getPercentage(): float
    {
        return $this->percentage;
    }

    /**
     * @param float $percentage
     */
    public function setPercentage(float $percentage): void
    {
        $this->percentage = $percentage;
    }

    /**
     * @return Valence
     */
    public function getValence(): Valence
    {
        return $this->valence;
    }

    /**
     * @param Valence $valence
     */
    public function setValence(Valence $valence): void
    {
        $this->valence = $valence;
    }

    /**
     * @return array<string, mixed>
     */
    #[ArrayShape([
        'percentage' => "float",
        'valence' => "\CliffordVickrey\MoyersBiggs\Domain\Enum\Valence"
    ])]
    public function jsonSerialize(): array
    {
        return ['percentage' => $this->percentage, 'valence' => $this->valence];
    }
}
