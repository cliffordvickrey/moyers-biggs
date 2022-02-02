<?php

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Domain\Repository;

use CliffordVickrey\MoyersBiggs\Domain\Entity\Event;
use CliffordVickrey\MoyersBiggs\Domain\Entity\EventCollection;
use CliffordVickrey\MoyersBiggs\Domain\Hydrator\EventHydrator;
use CliffordVickrey\MoyersBiggs\Infrastructure\Clock\Clock;
use CliffordVickrey\MoyersBiggs\Infrastructure\Clock\ClockInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Io\FileInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Io\Io;
use CliffordVickrey\MoyersBiggs\Infrastructure\Io\IoInterface;
use JetBrains\PhpStorm\Pure;

use function count;
use function is_array;
use function is_numeric;
use function json_decode;
use function json_encode;
use function max;
use function rsort;
use function sprintf;

use const JSON_PRETTY_PRINT;
use const SORT_NATURAL;

class EventRepository implements EventRepositoryInterface
{
    private ClockInterface $clock;
    private EventHydrator $hydrator;
    private IoInterface $io;

    /**
     * @param ClockInterface|null $clock
     * @param IoInterface|null $io
     */
    #[Pure]
    public function __construct(?ClockInterface $clock = null, ?IoInterface $io = null)
    {
        $this->clock = $clock ?? new Clock();
        $this->hydrator = new EventHydrator();
        $this->io = $io ?? new Io();
    }

    /**
     * @inheritDoc
     */
    public function save(Event $event): void
    {
        $questionnaireId = $event->getQuestionnaireId();

        $fileName = sprintf(
            __DIR__ . '/../../../data/log/event-%d-%s.json',
            $questionnaireId,
            $this->clock->now()->format('Y-m-d')
        );

        $file = $this->io->openFileForWriting($fileName);
        $eventCollection = $this->load($file);
        $eventCollection->unshift($event);

        $file->write(json_encode($eventCollection, JSON_PRETTY_PRINT) ?: '');
        $file->close();

        $this->incrementCount($questionnaireId, 1);
    }

    /**
     * @param FileInterface $file
     * @return EventCollection
     */
    private function load(FileInterface $file): EventCollection
    {
        $contents = $file->read();
        $data = json_decode($contents, true);

        if (!is_array($data)) {
            $data = [];
        }

        return $this->hydrator->hydrateMultiple($data);
    }

    /**
     * @param int<0, max> $questionnaireId
     * @param int<0, max> $delta
     * @return void
     */
    private function incrementCount(int $questionnaireId, int $delta): void
    {
        $filename = self::getCountFilename($questionnaireId);
        $file = $this->io->openFileForWriting($filename);
        $file->lock();

        $contents = $file->read();

        if (!is_numeric($contents)) {
            $contents = 0;
        }

        $count = ((int)$contents) + $delta;

        $file->write("$count");
        $file->close();
    }

    /**
     * @param int<0, max> $questionnaireId
     * @return string
     */
    private static function getCountFilename(int $questionnaireId): string
    {
        return sprintf(__DIR__ . '/../../../data/event-count/count-%d.txt', $questionnaireId);
    }

    /**
     * @inheritDoc
     */
    public function getCount(int $questionnaireId): int
    {
        $filename = self::getCountFilename($questionnaireId);

        if (!$this->io->exists($filename)) {
            $count = count($this->getEvents($questionnaireId));
            $this->incrementCount($questionnaireId, $count);
            return $count;
        }

        $file = $this->io->openFileForReading($filename);
        $file->lock();
        $contents = $file->read();
        $file->close();

        if (is_numeric($contents)) {
            $count = (int)$contents;
        } else {
            // @codeCoverageIgnoreStart
            $count = 0;
            // @codeCoverageIgnoreEnd
        }

        return max($count, 0);
    }

    /**
     * @inheritDoc
     */
    public function getEvents(int $questionnaireId, int $offset = 0, ?int $length = null): EventCollection
    {
        $filenames = $this->getLogFilenames($questionnaireId);

        $collection = new EventCollection();

        $totalToFetch = $length;

        if (null !== $totalToFetch) {
            $totalToFetch += $offset;
        }

        foreach ($filenames as $i => $fileName) {
            $file = $this->io->openFileForReading($fileName);

            if (0 === $i) {
                $file->lock();
            }

            $latestCollection = $this->load($file);
            $file->close();

            $collection->merge($latestCollection->toArray());

            if (null !== $totalToFetch && count($collection) >= $totalToFetch) {
                break;
            }
        }

        if (null === $length) {
            $length = count($collection);
        }

        $collection->slice($offset, $length);

        return $collection;
    }

    /**
     * @param int<0, max> $questionnaireId
     * @return list<string>
     */
    private function getLogFilenames(int $questionnaireId): array
    {
        $filenames = $this->io->glob(sprintf(__DIR__ . '/../../../data/log/event-%d-*.json', $questionnaireId));
        rsort($filenames, SORT_NATURAL);
        return $filenames;
    }
}
