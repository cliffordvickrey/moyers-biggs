<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\View;

/**
 * Encapsulates an HTTP response
 */
final class ViewModel
{
    public const DEFAULT_PARTIAL = 'home';

    /** @var array<string, mixed> */
    private array $params = [];
    private string $partial = self::DEFAULT_PARTIAL;
    private ?string $redirectTo = null;
    private ?string $routeTo = null;
    private int $statusCode = 200;

    /**
     * @return array<string, mixed>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setParam(string $name, mixed $value): void
    {
        $this->params[$name] = $value;
    }

    /**
     * @return string
     */
    public function getPartial(): string
    {
        return $this->partial;
    }

    /**
     * @param string $partial
     */
    public function setPartial(string $partial): void
    {
        $this->partial = $partial;
    }

    /**
     * @return string|null
     */
    public function getRedirectTo(): ?string
    {
        return $this->redirectTo;
    }

    /**
     * @param string|null $redirectTo
     */
    public function setRedirectTo(?string $redirectTo): void
    {
        $this->redirectTo = $redirectTo;
    }

    /**
     * @return string|null
     */
    public function getRouteTo(): ?string
    {
        return $this->routeTo;
    }

    /**
     * @param string|null $routeTo
     * @return void
     */
    public function setRouteTo(?string $routeTo): void
    {
        $this->routeTo = $routeTo;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        if (null !== $this->redirectTo) {
            return 302;
        }

        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }
}
