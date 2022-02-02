<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Factory\Domain\Repository;

use CliffordVickrey\MoyersBiggs\Domain\Entity\Questionnaire;
use CliffordVickrey\MoyersBiggs\Domain\Exception\UnexpectedValueException;
use CliffordVickrey\MoyersBiggs\Domain\Repository\QuestionnaireRepository;
use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\FactoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Io\IoInterface;

use function gettype;
use function is_array;
use function sprintf;

final class QuestionnaireRepositoryFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @return QuestionnaireRepository
     */
    public function build(DependencyInjectionContainerInterface $container): QuestionnaireRepository
    {
        $config = $container->getConfig();

        $questionnaireConfig = $config[Questionnaire::class] ?? null;

        if (!is_array($questionnaireConfig)) {
            throw new UnexpectedValueException(sprintf('Expected array; got %s', gettype($questionnaireConfig)));
        }

        return new QuestionnaireRepository(
            $questionnaireConfig,
            $container->getService(IoInterface::class)
        );
    }
}
