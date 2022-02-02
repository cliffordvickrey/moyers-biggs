<?php

/** @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection */

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Config;

use CliffordVickrey\MoyersBiggs\App\Controller\DefaultController;
use CliffordVickrey\MoyersBiggs\App\Controller\FrontController;
use CliffordVickrey\MoyersBiggs\App\Controller\LogController;
use CliffordVickrey\MoyersBiggs\App\Controller\QuestionController;
use CliffordVickrey\MoyersBiggs\App\Controller\ResultsController;
use CliffordVickrey\MoyersBiggs\App\Controller\SaveEventController;
use CliffordVickrey\MoyersBiggs\App\Controller\SaveFrequenciesController;
use CliffordVickrey\MoyersBiggs\App\Controller\StatsController;
use CliffordVickrey\MoyersBiggs\Domain\Entity\Questionnaire;
use CliffordVickrey\MoyersBiggs\Domain\Enum\Valence;
use CliffordVickrey\MoyersBiggs\Domain\Repository\EventRepository;
use CliffordVickrey\MoyersBiggs\Domain\Repository\EventRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Domain\Repository\QuestionnaireRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Domain\Repository\StateRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\DefaultControllerFactory;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\FrontControllerFactory;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\LogControllerFactory;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\QuestionControllerFactory;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\ResultsControllerFactory;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\SaveEventControllerFactory;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\SaveFrequenciesControllerFactory;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\App\Controller\StatsControllerFactory;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\Domain\Repository\EventRepositoryFactory;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\Domain\Repository\QuestionnaireRepositoryFactory;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\Domain\Repository\StateRepositoryFactory;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\Infrastructure\View\ViewRendererFactory;
use CliffordVickrey\MoyersBiggs\Infrastructure\Io\Io;
use CliffordVickrey\MoyersBiggs\Infrastructure\Io\IoInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Session\Session;
use CliffordVickrey\MoyersBiggs\Infrastructure\Session\SessionInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewRenderer;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewRendererInterface;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/**
 * Structured configuration for the application
 */
class ConfigProvider
{
    /**
     * @return array<string, mixed>
     */
    #[ArrayShape([
        'debug' => "false",
        DependencyInjectionContainerInterface::class => "mixed",
        LogController::class => "mixed",
        Questionnaire::class => "mixed",
        'root' => "string"
    ])]
    #[Pure]
    public function __invoke(): array
    {
        return [
            'debug' => false,
            DependencyInjectionContainerInterface::class => $this->getDependencies(),
            LogController::class => $this->getLogControllerConfig(),
            Questionnaire::class => $this->getQuestionnaireConfig(),
            'root' => '/'
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    #[ArrayShape([
        'aliases' => "string[]",
        'factories' => "string[]"
    ])]
    private function getDependencies(): array
    {
        return [
            'aliases' => [
                EventRepositoryInterface::class => EventRepository::class,
                IoInterface::class => Io::class,
                SessionInterface::class => Session::class,
                ViewRendererInterface::class => ViewRenderer::class
            ],
            'factories' => [
                DefaultController::class => DefaultControllerFactory::class,
                EventRepositoryInterface::class => EventRepositoryFactory::class,
                FrontController::class => FrontControllerFactory::class,
                LogController::class => LogControllerFactory::class,
                QuestionController::class => QuestionControllerFactory::class,
                QuestionnaireRepositoryInterface::class => QuestionnaireRepositoryFactory::class,
                ResultsController::class => ResultsControllerFactory::class,
                SaveEventController::class => SaveEventControllerFactory::class,
                SaveFrequenciesController::class => SaveFrequenciesControllerFactory::class,
                StateRepositoryInterface::class => StateRepositoryFactory::class,
                StatsController::class => StatsControllerFactory::class,
                ViewRendererInterface::class => ViewRendererFactory::class
            ]
        ];
    }

    /**
     * @return array<string, int>
     */
    #[ArrayShape(['resultsPerPage' => "int"])]
    private function getLogControllerConfig(): array
    {
        return ['resultsPerPage' => 30];
    }

    /**
     * @return array<mixed>
     */
    private function getQuestionnaireConfig(): array
    {
        return [[
            [
                'text' => 'What is the best way to come of age?',
                'answers' => [
                    [
                        'text' => 'Collecting the mail for Lyndon Johnson',
                        'valence' => Valence::MOYERS
                    ],
                    [
                        'text' => 'A freshly baked pie',
                        'valence' => Valence::BIGGS
                    ]
                ]
            ],
            [
                'text' => 'Your fiancée is imprisoned. What do you do?',
                'answers' => [
                    [
                        'text' => 'Stir up drama in her prison, end the engagement, and focus on writing',
                        'valence' => Valence::BIGGS
                    ],
                    [
                        'text' => 'Produce a video essay that examines the carceral state',
                        'valence' => Valence::MOYERS
                    ]
                ]
            ],
            [
                'text' => 'Which do you find to be the greater problem?',
                'answers' => [
                    [
                        'text' => 'The declining health of American democracy, beset by shadowy special interests',
                        'valence' => Valence::MOYERS
                    ],
                    [
                        'text' => 'A controlling significant other that threatens the existence of a Neil Diamond '
                            . 'tribute band',
                        'valence' => Valence::BIGGS
                    ]
                ]
            ],
            [
                'text' => 'You need cash, quickly. What is your course of action?',
                'answers' => [
                    [
                        'text' => 'Become the spokesperson of Mucinex™ Nightshift, formulated to fight your worst '
                            . 'symptoms so you can sleep great',
                        'valence' => Valence::BIGGS
                    ],
                    [
                        'text' => 'Rely on the generosity of viewers like you',
                        'valence' => Valence::MOYERS
                    ]
                ]
            ],
            [
                'text' => 'What coping strategy best helps you navigate the vagaries of life under modern capitalism?',
                'answers' => [
                    [
                        'text' => 'Engaging in an impassioned, hard-headed analysis of all that exists',
                        'valence' => Valence::MOYERS
                    ],
                    [
                        'text' => 'Having a random sense of humor',
                        'valence' => Valence::BIGGS
                    ]
                ]
            ]
        ]];
    }
}
