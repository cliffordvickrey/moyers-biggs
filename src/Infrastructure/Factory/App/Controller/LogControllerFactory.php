<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\LogController;
use CliffordVickrey\MoyersBiggs\Domain\Repository\EventRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Domain\Repository\QuestionnaireRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\FactoryInterface;

use function is_array;
use function is_numeric;

final class LogControllerFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @return LogController
     */
    public function build(DependencyInjectionContainerInterface $container): LogController
    {
        $config = $container->getConfig();

        $recordsPerPage = null;

        $logConfig = [];

        if (isset($config[LogController::class]) && is_array($config[LogController::class])) {
            $logConfig = $config[LogController::class];
        }

        if (isset($logConfig['recordsPerPage']) && is_numeric($logConfig['recordsPerPage'])) {
            $recordsPerPage = (int)$logConfig['recordsPerPage'];
        }

        if (null === $recordsPerPage) {
            $recordsPerPage = 30;
        }

        if ($recordsPerPage < 1) {
            $recordsPerPage = 1;
        }

        $timeZones = $logConfig['timeZones'] ?? null;

        if (!is_array($timeZones)) {
            $timeZones = null;
        }

        return new LogController(
            $container->getService(EventRepositoryInterface::class),
            $container->getService(QuestionnaireRepositoryInterface::class),
            $recordsPerPage,
            $timeZones
        );
    }
}
