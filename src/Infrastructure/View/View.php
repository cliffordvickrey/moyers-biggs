<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\View;

use ArrayObject;
use CliffordVickrey\MoyersBiggs\Infrastructure\Exception\IoException;
use CliffordVickrey\MoyersBiggs\Infrastructure\Io\Io;
use CliffordVickrey\MoyersBiggs\Infrastructure\Io\IoInterface;
use JetBrains\PhpStorm\Pure;

use function htmlentities;
use function ob_end_clean;
use function ob_get_contents;
use function ob_start;
use function sprintf;

use const ENT_QUOTES;

/**
 * The view. Runs a template with params
 *
 * @extends ArrayObject<string, mixed>
 */
final class View extends ArrayObject
{
    private IoInterface $io;
    private string $root;

    /**
     * @param array<string, mixed> $array
     * @param string|null $root
     * @param IoInterface|null $io
     */
    public function __construct(array $array = [], ?string $root = null, ?IoInterface $io = null)
    {
        $this->io = $io ?? new Io();
        $this->root = $root ?? '/';
        parent::__construct($array, ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function __invoke(string $fileName): string
    {
        if (!$this->io->exists($fileName)) {
            throw new IoException(sprintf('File %s does not exist', $fileName));
        }

        try {
            ob_start();
            require $fileName;
            return (string)ob_get_contents();
        } finally {
            ob_end_clean();
        }
    }

    /**
     * @param string $path
     * @return string
     */
    #[Pure]
    public function url(string $path): string
    {
        return $this->escape($this->root . $path);
    }

    /**
     * @param string $text
     * @return string
     */
    public function escape(string $text): string
    {
        return htmlentities($text, ENT_QUOTES);
    }

    /**
     * @param string $src
     * @return string
     */
    #[Pure]
    public function image(string $src): string
    {
        return $this->escape($this->root . 'images/' . $src);
    }

    /**
     * @param string $src
     * @return string
     */
    #[Pure]
    public function script(string $src): string
    {
        return $this->escape($this->root . 'js/' . $src);
    }
}
