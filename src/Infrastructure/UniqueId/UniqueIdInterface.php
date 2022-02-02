<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\UniqueId;

/**
 * Generates a random UUID
 */
interface UniqueIdInterface
{
    /**
     * @return string
     */
    public function getId(): string;
}
