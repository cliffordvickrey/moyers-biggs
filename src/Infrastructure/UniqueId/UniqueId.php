<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\UniqueId;

class UniqueId implements UniqueIdInterface
{
    private ?string $uuid;

    /**
     * @param string|null $uuid
     */
    public function __construct(?string $uuid = null)
    {
        $this->uuid = $uuid;
    }

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return $this->uuid ?? sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
