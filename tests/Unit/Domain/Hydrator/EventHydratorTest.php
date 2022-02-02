<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Domain\Hydrator;

use CliffordVickrey\MoyersBiggs\Domain\Exception\UnexpectedValueException;
use CliffordVickrey\MoyersBiggs\Domain\Hydrator\EventHydrator;
use PHPUnit\Framework\TestCase;
use stdClass;

use function json_decode;
use function json_encode;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Domain\Hydrator\EventHydrator
 */
class EventHydratorTest extends TestCase
{
    private EventHydrator $hydrator;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->hydrator = new EventHydrator();
    }

    /**
     * @return void
     */
    public function testHydrateMultiple(): void
    {
        $data = [
            [
                'id' => 'a9e0c12f-12d4-4610-af4c-b0c8279e9357',
                'questionnaireId' => 0,
                'startTime' => '2022-01-22T22:42:50+00:00',
                'stopTime' => '2022-01-22T22:42:52+00:00',
                'answerIds' => [
                    1,
                    1
                ]
            ],
            [
                'id' => '70e06c4a-e663-48b2-bfaa-ecba890c30d1',
                'questionnaireId' => 0,
                'startTime' => '2022-01-22T17:30:06+00:00',
                'stopTime' => '2022-01-22T17:30:06+00:00',
                'answerIds' => [
                    1,
                    0
                ]
            ]
        ];

        $eventCollection = $this->hydrator->hydrateMultiple($data);

        $extracted = json_decode((string)json_encode($eventCollection), true);

        self::assertEquals($data, $extracted);
    }

    /**
     * @return void
     */
    public function testHydrateMultipleInvalid(): void
    {
        $data = [new stdClass()];
        $this->expectException(UnexpectedValueException::class);
        $this->expectErrorMessage('Expected array of arrays');
        $this->hydrator->hydrateMultiple($data);
    }

    /**
     * @return void
     */
    public function testHydrateInvalidId(): void
    {
        $data = [
            'id' => new stdClass(),
            'questionnaireId' => 0,
            'startTime' => '2022-01-22T22:42:50+00:00',
            'stopTime' => '2022-01-22T22:42:52+00:00',
            'answerIds' => [
                1,
                1
            ]
        ];

        $event = $this->hydrator->hydrate($data);
        self::assertEquals('', $event->getId());
    }

    /**
     * @return void
     */
    public function testHydrateInvalidQuestionnaireId(): void
    {
        $data = [
            'id' => '70e06c4a-e663-48b2-bfaa-ecba890c30d1',
            'questionnaireId' => new stdClass(),
            'startTime' => '2022-01-22T22:42:50+00:00',
            'stopTime' => '2022-01-22T22:42:52+00:00',
            'answerIds' => [
                1,
                1
            ]
        ];

        $event = $this->hydrator->hydrate($data);
        self::assertEquals(0, $event->getQuestionnaireId());
    }

    /**
     * @return void
     */
    public function testHydrateNegativeQuestionnaireId(): void
    {
        $data = [
            'id' => '70e06c4a-e663-48b2-bfaa-ecba890c30d1',
            'questionnaireId' => -1,
            'startTime' => '2022-01-22T22:42:50+00:00',
            'stopTime' => '2022-01-22T22:42:52+00:00',
            'answerIds' => [
                1,
                1
            ]
        ];

        $event = $this->hydrator->hydrate($data);
        self::assertEquals(0, $event->getQuestionnaireId());
    }

    /**
     * @return void
     */
    public function testHydrateInvalidStartTime(): void
    {
        $data = [
            'id' => '70e06c4a-e663-48b2-bfaa-ecba890c30d1',
            'questionnaireId' => 0,
            'startTime' => new stdClass(),
            'stopTime' => '2022-01-22T22:42:52+00:00',
            'answerIds' => [
                1,
                1
            ]
        ];

        $this->expectException(UnexpectedValueException::class);
        $this->expectErrorMessage('Expected "startTime" to be a string');
        $this->hydrator->hydrate($data);
    }

    /**
     * @return void
     */
    public function testHydrateInvalidStopTime(): void
    {
        $data = [
            'id' => '70e06c4a-e663-48b2-bfaa-ecba890c30d1',
            'questionnaireId' => 0,
            'startTime' => '2022-01-22T22:42:50+00:00',
            'stopTime' => new stdClass(),
            'answerIds' => [
                1,
                1
            ]
        ];

        $this->expectException(UnexpectedValueException::class);
        $this->expectErrorMessage('Expected "stopTime" to be a string');
        $this->hydrator->hydrate($data);
    }

    /**
     * @return void
     */
    public function testHydrateInvalidAnswerIds(): void
    {
        $data = [
            'id' => '70e06c4a-e663-48b2-bfaa-ecba890c30d1',
            'questionnaireId' => 0,
            'startTime' => '2022-01-22T22:42:50+00:00',
            'stopTime' => '2022-01-22T22:42:52+00:00',
            'answerIds' => new stdClass()
        ];

        $this->expectException(UnexpectedValueException::class);
        $this->expectErrorMessage('Expected "answerIds" to be an array');
        $this->hydrator->hydrate($data);
    }
}
