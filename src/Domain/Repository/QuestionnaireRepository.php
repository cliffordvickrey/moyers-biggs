<?php

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Domain\Repository;

use CliffordVickrey\MoyersBiggs\Domain\Entity\Questionnaire;
use CliffordVickrey\MoyersBiggs\Domain\Exception\OutOfBoundException;
use CliffordVickrey\MoyersBiggs\Domain\Exception\UnexpectedValueException;
use CliffordVickrey\MoyersBiggs\Domain\Hydrator\QuestionnaireHydrator;
use CliffordVickrey\MoyersBiggs\Infrastructure\Io\Io;
use CliffordVickrey\MoyersBiggs\Infrastructure\Io\IoInterface;
use JetBrains\PhpStorm\Pure;

use function gettype;
use function is_array;
use function json_decode;
use function json_encode;
use function sprintf;

class QuestionnaireRepository implements QuestionnaireRepositoryInterface
{
    /** @var array<mixed> */
    private array $config;
    private QuestionnaireHydrator $hydrator;
    private IoInterface $io;
    /** @var array<int, Questionnaire> */
    private array $questionnaires = [];

    /**
     * @param array<mixed> $config
     */
    #[Pure]
    public function __construct(array $config, ?IoInterface $io = null)
    {
        $this->config = $config;
        $this->hydrator = new QuestionnaireHydrator();
        $this->io = $io ?? new Io();
    }

    /**
     * @inheritDoc
     */
    public function getQuestionnaireById(int $id, bool $withFrequencies = false): Questionnaire
    {
        $questionnaire = $this->doGetQuestionnaireById($id);

        if (!$withFrequencies) {
            return $questionnaire;
        }

        $questionnaireWithFrequencies = clone $questionnaire;

        $frequenciesStruct = $this->getFrequenciesStruct($id);

        foreach ($frequenciesStruct as $questionId => $frequencies) {
            foreach ($frequencies as $answerId => $frequency) {
                $questionnaireWithFrequencies[$questionId][$answerId]->setFrequency($frequency);
            }
        }

        return $questionnaireWithFrequencies;
    }

    /**
     * @param int<0, max> $id
     * @return Questionnaire
     */
    private function doGetQuestionnaireById(int $id): Questionnaire
    {
        if (isset($this->questionnaires[$id])) {
            return $this->questionnaires[$id];
        }

        if (!isset($this->config[$id])) {
            throw new OutOfBoundException(sprintf('Questionnaire with id "%d" not found', $id));
        }

        $config = $this->config[$id];

        if (!is_array($config)) {
            $msg = sprintf('Expected array for questionnaire config; got %s', gettype($config));
            throw new UnexpectedValueException($msg);
        }

        $questionnaire = $this->hydrator->hydrate($config);
        $questionnaire->setId($id);
        $this->questionnaires[$id] = $questionnaire;
        return $questionnaire;
    }

    /**
     * @param int<0, max> $id
     * @return array<int, array<int, int<0, max>>>
     */
    private function getFrequenciesStruct(int $id): array
    {
        $fileName = self::getFileName($id);

        if (!$this->io->exists($fileName)) {
            return [];
        }

        $file = $this->io->openFileForReading($fileName);
        $file->lock();
        $json = json_decode($file->read(), true);
        $file->close();

        if (!is_array($json)) {
            // @codeCoverageIgnoreStart
            return [];
            // @codeCoverageIgnoreEnd
        }

        return $json;
    }

    /**
     * @param int<0, max> $id
     * @return string
     */
    private static function getFileName(int $id): string
    {
        return sprintf(__DIR__ . '/../../../data/frequencies/frequencies-%d.json', $id);
    }

    /**
     * @param Questionnaire $questionnaire
     * @return void
     */
    public function saveQuestionnaireFrequencies(Questionnaire $questionnaire): void
    {
        $file = $this->io->openFileForWriting(self::getFileName($questionnaire->getId()));
        $file->lock();
        $file->write(json_encode($questionnaire) ?: '[]');
        $file->close();
    }
}
