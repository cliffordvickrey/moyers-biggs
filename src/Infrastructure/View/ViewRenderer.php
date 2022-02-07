<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\View;

use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HeaderEmitter;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HeaderEmitterInterface;
use JetBrains\PhpStorm\Pure;

use function sprintf;

use const DIRECTORY_SEPARATOR;

final class ViewRenderer implements ViewRendererInterface
{
    private HeaderEmitterInterface $headerEmitter;
    private string $version;
    private string $root;
    private string $templatePath;

    /**
     * @param string|null $root
     * @param string|null $version
     * @param string|null $templatePath
     * @param HeaderEmitterInterface|null $headerEmitter
     */
    #[Pure]
    public function __construct(
        ?string $root = null,
        ?string $version = null,
        ?string $templatePath = null,
        ?HeaderEmitterInterface $headerEmitter = null
    ) {
        $this->headerEmitter = $headerEmitter ?? new HeaderEmitter();
        $this->root = $root ?? '/';
        $this->version = $version ?? '1.0';
        $this->templatePath = $templatePath ?? (__DIR__ . '/../../../templates');
    }

    /**
     * @param ViewModel $viewModel
     * @return string
     */
    public function render(ViewModel $viewModel): string
    {
        $view = new View($viewModel->getParams(), $this->root);
        $partial = $viewModel->getPartial();
        $fileName = $this->getPartialFilename($partial);

        $redirectTo = $viewModel->getRedirectTo();

        if (null !== $redirectTo) {
            return $this->doRedirect($viewModel);
        }

        $content = $view($fileName);

        if ('layout' === $partial) {
            return $content;
        }

        $this->emitHeaders($viewModel);
        return $this->renderLayout($content, $viewModel);
    }

    /**
     * @param string $partial
     * @return string
     */
    private function getPartialFilename(string $partial): string
    {
        return sprintf(
            '%s%s%s.phtml',
            $this->templatePath,
            DIRECTORY_SEPARATOR,
            preg_replace('/[^a-z0-9]+/', '-', strtolower($partial))
        );
    }

    /**
     * @param ViewModel $viewModel
     * @return string
     */
    private function doRedirect(ViewModel $viewModel): string
    {
        $this->emitHeaders($viewModel);
        return '';
    }

    /**
     * @param ViewModel $viewModel
     * @return void
     */
    private function emitHeaders(ViewModel $viewModel): void
    {
        $status = $viewModel->getStatusCode();
        $this->headerEmitter->emitHeader('Content-Type: text/html; charset=utf-8', true, $status);
        $this->headerEmitter->emitHeader('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        $this->headerEmitter->emitHeader('Cache-Control: post-check=0, pre-check=0', false);

        $redirectTo = $viewModel->getRedirectTo();
        if (null !== $redirectTo) {
            $this->headerEmitter->emitHeader(sprintf('Location: %s%s', $this->root, $redirectTo));
        }
    }

    /**
     * @param string $content
     * @param ViewModel $child
     * @return string
     */
    private function renderLayout(string $content, ViewModel $child): string
    {
        $viewModel = new ViewModel();
        $viewModel->setPartial('layout');
        $viewModel->setParam('content', $content);
        $viewModel->setParam('child', $child);
        $viewModel->setParam('version', $this->version);
        return $this->render($viewModel);
    }
}
