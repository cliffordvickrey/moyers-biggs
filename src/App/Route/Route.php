<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\App\Route;

/**
 * Constants for HTTP request attributes
 */
class Route
{
    public const ATTRIBUTE_ANSWER_ID = 'answerId';
    public const ATTRIBUTE_PAGE = 'page';
    public const ATTRIBUTE_TIME_ZONE = 'timeZoneId';
    public const ATTRIBUTE_QUESTION_ID = 'questionId';
    public const ATTRIBUTE_QUESTIONNAIRE_ID = 'questionnaireId';
    public const ROUTE_DEFAULT = 'default';
    public const ROUTE_LOG = 'log';
    public const ROUTE_NONE = 'none';
    public const ROUTE_QUESTION = 'question';
    public const ROUTE_SAVE_EVENT = 'saveEvent';
    public const ROUTE_SAVE_FREQUENCIES = 'saveFrequencies';
    public const ROUTE_STATS = 'stats';
    public const ROUTE_RESULTS = 'results';
}
