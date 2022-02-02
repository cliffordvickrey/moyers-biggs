<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Unit\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Controller\DefaultController;
use CliffordVickrey\MoyersBiggs\Domain\Repository\StateRepository;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Session\MockSession;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CliffordVickrey\MoyersBiggs\App\Controller\DefaultController
 */
class DefaultControllerTest extends TestCase
{
    /**
     * @return void
     */
    public function testDispatch(): void
    {
        $controller = new DefaultController(new StateRepository(new MockSession()));
        $viewModel = $controller->dispatch(new HttpRequest());
        self::assertEquals('default', $viewModel->getPartial());
    }
}
