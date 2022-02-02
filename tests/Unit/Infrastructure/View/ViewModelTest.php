<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\Infrastructure\View;

use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewModel;
use PHPUnit\Framework\TestCase;

class ViewModelTest extends TestCase
{
    private ViewModel $viewModel;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->viewModel = new ViewModel();
    }

    /**
     * @return void
     */
    public function testPartial(): void
    {
        $this->viewModel->setPartial('test');
        self::assertEquals('test', $this->viewModel->getPartial());
    }

    /**
     * @return void
     */
    public function testSetParam(): void
    {
        $this->viewModel->setParam('test', true);
        self::assertEquals(['test' => true], $this->viewModel->getParams());
    }

    /**
     * @return void
     */
    public function testSetRedirectTo(): void
    {
        $this->viewModel->setRedirectTo('/test');
        self::assertEquals('/test', $this->viewModel->getRedirectTo());
    }

    /**
     * @return void
     */
    public function testSetRouteTo(): void
    {
        $this->viewModel->setRouteTo('test');
        self::assertEquals('test', $this->viewModel->getRouteTo());
    }

    /**
     * @return void
     */
    public function testSetStatusCode(): void
    {
        $this->viewModel->setStatusCode(404);
        self::assertEquals(404, $this->viewModel->getStatusCode());
        $this->viewModel->setRedirectTo('/test');
        self::assertEquals(302, $this->viewModel->getStatusCode());
    }
}
