<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Session;

/**
 * Methods for the PHP session
 */
interface SessionInterface
{
    /**
     * @return void
     */
    public function destroy(): void;

    /**
     * @return string|null
     */
    public function get(): ?string;

    /**
     * @param string $data
     * @return void
     */
    public function save(string $data): void;
}
