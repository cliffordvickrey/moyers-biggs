<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Infrastructure\View;

use CliffordVickrey\MoyersBiggs\Infrastructure\Exception\IoException;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\View;
use PHPUnit\Framework\TestCase;

use function call_user_func;

class ViewTest extends TestCase
{
    private View $view;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->view = new View();
    }

    /**
     * @return void
     */
    public function testInvoke(): void
    {
        $contents = call_user_func($this->view, __DIR__ . '/view.phtml');
        self::assertEquals('Hello world!', $contents);
    }

    /**
     * @return void
     */
    public function testInvokeFileNotFound(): void
    {
        $this->expectException(IoException::class);
        $this->expectExceptionMessageMatches('/does not exist$/');
        call_user_func($this->view, __DIR__ . '/does-not-exist.phtml');
    }

    /**
     * @return void
     */
    public function testEscape(): void
    {
        self::assertEquals('&quot;B &amp; O Railroad&quot;', $this->view->escape('"B & O Railroad"'));
    }

    /**
     * @return void
     */
    public function testUrl(): void
    {
        self::assertEquals('/path', $this->view->url('path'));
    }

    /**
     * @return void
     */
    public function testImage(): void
    {
        self::assertEquals('/images/something.jpg', $this->view->image('something.jpg'));
    }

    /**
     * @return void
     */
    public function testScript(): void
    {
        self::assertEquals('/js/test.js', $this->view->script('test.js'));
    }
}
