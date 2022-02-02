<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Mock\Domain\Repository;

use CliffordVickrey\MoyersBiggs\Domain\Entity\Answer;
use CliffordVickrey\MoyersBiggs\Domain\Entity\Question;
use CliffordVickrey\MoyersBiggs\Domain\Entity\Questionnaire;
use CliffordVickrey\MoyersBiggs\Domain\Enum\Valence;
use CliffordVickrey\MoyersBiggs\Domain\Exception\OutOfBoundException;
use CliffordVickrey\MoyersBiggs\Domain\Repository\QuestionnaireRepositoryInterface;

class MockQuestionnaireRepository implements QuestionnaireRepositoryInterface
{
    /** @var list<Questionnaire> */
    private array $questionnaires;

    /**
     *
     */
    public function __construct()
    {
        $questionnaire = new Questionnaire();

        $question = new Question('Q1');
        $question[] = new Answer('Q1/A1', new Valence(Valence::MOYERS));
        $question[] = new Answer('Q1/A2', new Valence(Valence::BIGGS));
        $questionnaire[] = $question;

        $question = new Question('Q2');
        $question[] = new Answer('Q1/A1', new Valence(Valence::MOYERS));
        $question[] = new Answer('Q2/A2', new Valence(Valence::BIGGS));
        $questionnaire[] = $question;

        $this->questionnaires = [$questionnaire];
    }

    /**
     * @inheritDoc
     */
    public function getQuestionnaireById(int $id, bool $withFrequencies = false): Questionnaire
    {
        $questionnaire = $this->questionnaires[$id] ?? null;

        if (null === $questionnaire) {
            throw new OutOfBoundException('Questionnaire not found');
        }

        return $questionnaire;
    }

    /**
     * @inheritDoc
     */
    public function saveQuestionnaireFrequencies(Questionnaire $questionnaire): void
    {
        $this->questionnaires[$questionnaire->getId()] = $questionnaire;
    }
}
