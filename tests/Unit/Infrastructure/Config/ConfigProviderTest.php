<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Infrastructure\Config;

use CliffordVickrey\MoyersBiggs\App\Controller\LogController;
use CliffordVickrey\MoyersBiggs\Domain\Entity\Questionnaire;
use CliffordVickrey\MoyersBiggs\Infrastructure\Config\ConfigProvider;
use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainerInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Infrastructure\Config\ConfigProvider
 */
class ConfigProviderTest extends TestCase
{
    /**
     * @return void
     */
    public function testInvoke(): void
    {
        $config = (new ConfigProvider())();
        self::assertArrayHasKey('debug', $config);
        self::assertArrayHasKey(DependencyInjectionContainerInterface::class, $config);
        self::assertArrayHasKey(LogController::class, $config);
        self::assertArrayHasKey(Questionnaire::class, $config);
        self::assertArrayHasKey('root', $config);
    }
}
