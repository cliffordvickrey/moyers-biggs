<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Domain\Entity;

use CliffordVickrey\MoyersBiggs\Domain\Enum\Valence;

/**
 * The answer entity
 */
final class Answer
{
    private string $text;
    private Valence $valence;
    /** @var positive-int */
    private int $intensity;
    /** @var int<0, max> */
    private int $frequency;

    /**
     * @param string $text
     * @param Valence $valence
     * @param positive-int $intensity
     * @param int<0, max> $frequency
     */
    public function __construct(
        string $text,
        Valence $valence,
        int $intensity = 1,
        int $frequency = 0
    ) {
        $this->text = $text;
        $this->valence = $valence;
        $this->intensity = $intensity;
        $this->frequency = $frequency;
    }

    /**
     * @return void
     */
    public function __clone(): void
    {
        $this->valence = clone $this->valence;
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
     * @return int
     */
    public function getIntensity(): int
    {
        return $this->intensity;
    }

    /**
     * @param positive-int $intensity
     */
    public function setIntensity(int $intensity): void
    {
        $this->intensity = $intensity;
    }

    /**
     * @return int
     */
    public function getFrequency(): int
    {
        return $this->frequency;
    }

    /**
     * @param int<0, max> $frequency
     */
    public function setFrequency(int $frequency): void
    {
        $this->frequency = $frequency;
    }
}
