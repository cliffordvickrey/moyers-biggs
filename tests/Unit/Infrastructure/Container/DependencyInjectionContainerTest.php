<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Infrastructure\Container;

use CliffordVickrey\MoyersBiggs\App\Controller\ErrorController;
use CliffordVickrey\MoyersBiggs\App\Controller\FrontController;
use CliffordVickrey\MoyersBiggs\App\Controller\LogController;
use CliffordVickrey\MoyersBiggs\Domain\Repository\EventRepository;
use CliffordVickrey\MoyersBiggs\Domain\Repository\EventRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainer;
use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Exception\ContainerException;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\FrontControllerFactory;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\Domain\Repository\EventRepositoryFactory;
use CliffordVickrey\MoyersBiggs\Infrastructure\Io\IoInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Session\SessionInterface;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Io\MockIo;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Session\MockSession;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainer
 */
class DependencyInjectionContainerTest extends TestCase
{
    private DependencyInjectionContainer $container;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->container = new DependencyInjectionContainer([
            DependencyInjectionContainerInterface::class => [
                'aliases' => [
                    IoInterface::class => MockIo::class,
                    SessionInterface::class => MockSession::class
                ],
                'factories' => [
                    EventRepositoryInterface::class => EventRepositoryFactory::class,
                    FrontController::class => FrontControllerFactory::class,
                    ErrorController::class => ErrorController::class,
                    LogController::class => EventRepositoryFactory::class
                ]
            ]
        ]);
    }

    /**
     * @return void
     */
    public function testGetConfig(): void
    {
        $config = $this->container->getConfig();
        self::assertArrayHasKey(DependencyInjectionContainerInterface::class, $config);
    }

    /**
     * @return void
     */
    public function testGetServiceFromAlias(): void
    {
        $session = $this->container->getService(SessionInterface::class);
        self::assertInstanceOf(MockSession::class, $session);
        $session = $this->container->getService(SessionInterface::class);
        self::assertInstanceOf(MockSession::class, $session);
        $io = $this->container->getService(IoInterface::class);
        self::assertInstanceOf(MockIo::class, $io);
    }

    /**
     * @return void
     */
    public function testGetServiceFromFactory(): void
    {
        $frontController = $this->container->getService(FrontController::class);
        self::assertInstanceOf(FrontController::class, $frontController);
        $frontController = $this->container->getService(FrontController::class);
        self::assertInstanceOf(FrontController::class, $frontController);
        $eventRepository = $this->container->getService(EventRepositoryInterface::class);
        self::assertInstanceOf(EventRepository::class, $eventRepository);
    }

    /**
     * @return void
     */
    public function testGetServiceFromBadFactory(): void
    {
        $this->expectException(ContainerException::class);
        $this->expectErrorMessage('Class CliffordVickrey\MoyersBiggs\App\Controller\ErrorController does not implement '
            . 'CliffordVickrey\MoyersBiggs\Infrastructure\Factory\FactoryInterface');
        $this->container->getService(ErrorController::class);
    }

    /**
     * @return void
     */
    public function testGetServiceFromMisMatchedFactory(): void
    {
        $this->expectException(ContainerException::class);
        $this->expectErrorMessage('Object (CliffordVickrey\MoyersBiggs\Domain\Repository\EventRepository) is not an '
            . 'instance of CliffordVickrey\MoyersBiggs\App\Controller\LogController');
        $this->container->getService(LogController::class);
    }

    /**
     * @return void
     */
    public function testGetServiceFromClassName(): void
    {
        $io = $this->container->getService(MockIo::class);
        self::assertInstanceOf(MockIo::class, $io);
    }

    /**
     * @return void
     */
    public function testGetServiceFromFactoryNoConfig(): void
    {
        $container = new DependencyInjectionContainer([]);
        $this->expectException(ContainerException::class);
        $this->expectErrorMessage('Class CliffordVickrey\MoyersBiggs\Infrastructure\Session\SessionInterface does not '
            . 'exist');
        $container->getService(SessionInterface::class);
    }

    /**
     * @return void
     */
    public function testGetServiceFromFactoryInvalidConfig(): void
    {
        $container = new DependencyInjectionContainer([
            DependencyInjectionContainerInterface::class => new stdClass()
        ]);
        $this->expectException(ContainerException::class);
        $this->expectErrorMessage('Class CliffordVickrey\MoyersBiggs\Infrastructure\Session\SessionInterface does not '
            . 'exist');
        $container->getService(SessionInterface::class);
    }

    /**
     * @return void
     */
    public function testGetServiceFromFactoryNoFactoryConfig(): void
    {
        $container = new DependencyInjectionContainer([
            DependencyInjectionContainerInterface::class => [
                'factories' => new stdClass()
            ]
        ]);
        $this->expectException(ContainerException::class);
        $this->expectErrorMessage('Class CliffordVickrey\MoyersBiggs\Infrastructure\Session\SessionInterface does not '
            . 'exist');
        $container->getService(SessionInterface::class);
    }

    /**
     * @return void
     */
    public function testGetServiceFromAliasNoAliasConfig(): void
    {
        $container = new DependencyInjectionContainer([
            DependencyInjectionContainerInterface::class => [
                'aliases' => new stdClass()
            ]
        ]);
        $this->expectException(ContainerException::class);
        $this->expectErrorMessage('Class CliffordVickrey\MoyersBiggs\Infrastructure\Session\SessionInterface does not '
            . 'exist');
        $container->getService(SessionInterface::class);
    }
}
