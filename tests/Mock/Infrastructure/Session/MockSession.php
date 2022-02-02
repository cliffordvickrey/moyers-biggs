<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Tests\Mock\Infrastructure\Session;

use CliffordVickrey\MoyersBiggs\Infrastructure\Session\SessionInterface;

class MockSession implements SessionInterface
{
    private ?string $data = null;

    /**
     * @inheritDoc
     */
    public function destroy(): void
    {
        $this->data = null;
    }

    /**
     * @inheritDoc
     */
    public function get(): ?string
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function save(string $data): void
    {
        $this->data = $data;
    }
}
