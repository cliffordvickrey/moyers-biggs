<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Controller;

use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewModel;

interface ControllerInterface
{
    /**
     * Handle a request
     * @param HttpRequest $request
     * @return ViewModel
     */
    public function dispatch(HttpRequest $request): ViewModel;
}
