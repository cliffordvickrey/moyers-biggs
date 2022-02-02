<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Domain\Repository;

use CliffordVickrey\MoyersBiggs\Domain\Entity\Questionnaire;

interface QuestionnaireRepositoryInterface
{
    /**
     * Get a questionnaire
     * @param int<0, max> $id
     * @param bool $withFrequencies
     * @return Questionnaire
     */
    public function getQuestionnaireById(int $id, bool $withFrequencies = false): Questionnaire;

    /**
     * Save questionnaire's frequencies for the stats page
     * @param Questionnaire $questionnaire
     * @return void
     */
    public function saveQuestionnaireFrequencies(Questionnaire $questionnaire): void;
}
