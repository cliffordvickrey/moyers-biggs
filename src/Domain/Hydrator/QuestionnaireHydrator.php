<?php

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Domain\Hydrator;

use CliffordVickrey\MoyersBiggs\Domain\Entity\Answer;
use CliffordVickrey\MoyersBiggs\Domain\Entity\Question;
use CliffordVickrey\MoyersBiggs\Domain\Entity\Questionnaire;
use CliffordVickrey\MoyersBiggs\Domain\Enum\Valence;
use CliffordVickrey\MoyersBiggs\Domain\Exception\UnexpectedValueException;

use function is_array;
use function is_numeric;
use function is_string;
use function sprintf;

/**
 * Converts a questionnaire to an array and vice-versa
 */
final class QuestionnaireHydrator
{
    /**
     * @param array<mixed> $config
     * @return Questionnaire
     */
    public function hydrate(array $config): Questionnaire
    {
        $questionnaire = new Questionnaire();

        foreach ($config as $questionConfig) {
            if (!is_array($questionConfig)) {
                $msg = sprintf('Expected array for question config; got %s', gettype($questionConfig));
                throw new UnexpectedValueException($msg);
            }

            $questionnaire[] = self::buildQuestion($questionConfig);
        }

        return $questionnaire;
    }

    /**
     * @param array<mixed> $config
     * @return Question
     */
    private static function buildQuestion(array $config): Question
    {
        $text = $config['text'] ?? null;

        if (!is_string($text)) {
            $msg = sprintf('Expected string for question text; got %s', gettype($text));
            throw new UnexpectedValueException($msg);
        }

        $answerConfigs = $config['answers'] ?? null;

        if (!is_array($answerConfigs)) {
            $msg = sprintf('Expected array for question answers; got %s', gettype($answerConfigs));
            throw new UnexpectedValueException($msg);
        }

        $question = new Question($text);

        foreach ($answerConfigs as $answerConfig) {
            $question[] = self::buildAnswer($answerConfig);
        }

        if (0 === count($question)) {
            throw new UnexpectedValueException('Question has no answers associated with it');
        }

        return $question;
    }

    /**
     * @param array<mixed> $config
     * @return Answer
     */
    private static function buildAnswer(array $config): Answer
    {
        $text = $config['text'] ?? null;

        if (!is_string($text)) {
            $msg = sprintf('Expected string for answer text; got %s', gettype($text));
            throw new UnexpectedValueException($msg);
        }

        $valence = $config['valence'] ?? null;

        if (!is_string($valence) && !($valence instanceof Valence)) {
            $msg = sprintf(
                'Expected string or instance of %s for answer valence; got %s',
                Valence::class,
                gettype($text)
            );
            throw new UnexpectedValueException($msg);
        }

        $intensity = $config['intensity'] ?? 1;

        if (!is_numeric($intensity)) {
            $msg = sprintf('Expected numeric value or NULL for answer intensity; got %s', gettype($text));
            throw new UnexpectedValueException($msg);
        }

        $intensity = (int)$intensity;

        if ($intensity < 1) {
            throw new UnexpectedValueException('Answer intensity cannot be less than 1');
        }

        return new Answer($text, is_string($valence) ? new Valence($valence) : $valence, $intensity);
    }
}
