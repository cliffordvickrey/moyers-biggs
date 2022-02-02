<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Infrastructure\Http;

use CliffordVickrey\MoyersBiggs\App\Route\Route;
use CliffordVickrey\MoyersBiggs\Domain\Exception\UnexpectedValueException;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use PHPUnit\Framework\TestCase;
use stdClass;

class HttpRequestTest extends TestCase
{
    private HttpRequest $httpRequest;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->httpRequest = new HttpRequest();
    }

    /**
     * @return void
     */
    public function testGetRoute(): void
    {
        self::assertEquals(Route::ROUTE_NONE, $this->httpRequest->getRoute());
    }

    /**
     * @return void
     */
    public function testGetAttribute(): void
    {
        $this->httpRequest->setAttribute('blah', 1);
        self::assertEquals(1, $this->httpRequest->getAttribute('blah'));
    }

    /**
     * @return void
     */
    public function testGetId(): void
    {
        self::assertNull($this->httpRequest->getId('id'));

        $this->httpRequest->setAttribute('id', -1);

        self::assertNull($this->httpRequest->getId('id'));

        $this->httpRequest->setAttribute('id', 1);

        self::assertEquals(1, $this->httpRequest->getId('id'));
    }

    /**
     * @return void
     */
    public function testGetEntity(): void
    {
        $this->httpRequest->setAttribute(stdClass::class, new stdClass());
        self::assertInstanceOf(stdClass::class, $this->httpRequest->getEntity(stdClass::class));
    }

    /**
     * @return void
     */
    public function testGetEntityInvalid(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Expected instance of stdClass');
        $this->httpRequest->getEntity(stdClass::class);
    }
}
