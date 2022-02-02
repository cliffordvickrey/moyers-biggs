<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Infrastructure\UniqueId;

use CliffordVickrey\MoyersBiggs\Infrastructure\UniqueId\UniqueId;
use PHPUnit\Framework\TestCase;

use function strlen;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Infrastructure\UniqueId\UniqueId
 */
class UniqueIdTest extends TestCase
{
    /**
     * @return void
     */
    public function testGetId(): void
    {
        $uniqueId = new UniqueId();
        $id = $uniqueId->getId();
        self::assertEquals(36, strlen($id));
    }
}
