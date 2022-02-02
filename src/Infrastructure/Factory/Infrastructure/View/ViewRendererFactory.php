<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Factory\Infrastructure\View;

use CliffordVickrey\MoyersBiggs\Infrastructure\Container\DependencyInjectionContainerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Factory\FactoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewRenderer;

use function is_string;

class ViewRendererFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     * @return ViewRenderer
     */
    public function build(DependencyInjectionContainerInterface $container): ViewRenderer
    {
        $config = $container->getConfig();
        $root = $config['root'] ?? null;

        if (!is_string($root)) {
            $root = null;
        }

        return new ViewRenderer($root);
    }
}
