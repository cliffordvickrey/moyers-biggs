<?php

declare(strict_types=1);

use CliffordVickrey\MoyersBiggs\App\Controller\FrontController;
use CliffordVickrey\MoyersBiggs\App\Error\ErrorHandler;
use CliffordVickrey\MoyersBiggs\App\Route\Route;
use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainer;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;

chdir(__DIR__);

// composer autoloader
require_once '../vendor/autoload.php';

call_user_func(function () {
    // error handler
    set_error_handler([ErrorHandler::class, 'handle']);

    $config = require 'config.php';

    $root = '/';

    if (is_string($config['root'] ?? null)) {
        $root = $config['root'];
    }

    $path = preg_replace(
        sprintf('/^%s/', preg_quote($root, '/')),
        '',
        (string)parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
    );

    list ($route, $param) = array_pad(explode('/', $path), 2, '');

    switch ($route) {
        case '':
            $route = Route::ROUTE_DEFAULT;
            break;
        case Route::ROUTE_LOG:
            $route = Route::ROUTE_LOG;
            $attributes = [Route::ATTRIBUTE_PAGE => filter_var($param, FILTER_SANITIZE_NUMBER_INT)];
            break;
        case Route::ROUTE_QUESTION:
            $route = Route::ROUTE_QUESTION;
            $attributes = [
                Route::ATTRIBUTE_ANSWER_ID => filter_input(INPUT_GET, 'a', FILTER_SANITIZE_NUMBER_INT),
                Route::ATTRIBUTE_QUESTION_ID => filter_var($param, FILTER_SANITIZE_NUMBER_INT)
            ];
            break;
        case Route::ROUTE_STATS:
            $route = Route::ROUTE_STATS;
            break;
        default:
            $route = Route::ROUTE_NONE;
    }

    (new DependencyInjectionContainer($config))
        ->getService(FrontController::class)
        ->dispatchAndEmit(new HttpRequest($route, $attributes ?? []));
});
