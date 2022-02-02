<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\View;

/**
 * Emits an HTTP response
 */
interface ViewRendererInterface
{
    /**
     * @param ViewModel $viewModel
     * @return string
     */
    public function render(ViewModel $viewModel): string;
}
