<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Infrastructure\Session;

use function ini_get;
use function is_string;
use function session_destroy;
use function session_get_cookie_params;
use function session_name;
use function session_start;
use function session_status;
use function session_write_close;

use const PHP_SESSION_ACTIVE;

/**
 * @codeCoverageIgnore
 */
class Session implements SessionInterface
{
    /**
     * @inheritDoc
     */
    public function get(): ?string
    {
        $this->startSession();

        $sessionData = $_SESSION[self::class] ?? null;

        if (is_string($sessionData)) {
            return $sessionData;
        }

        return null;
    }

    /**
     * @return void
     */
    private function startSession(): void
    {
        if (!$this->isSessionStarted()) {
            session_start();
        }
    }

    /**
     * @return bool
     */
    private function isSessionStarted(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * @inheritDoc
     */
    public function destroy(): void
    {
        if ($this->isSessionStarted()) {
            session_destroy();
        }

        if (!ini_get('session.use_cookies')) {
            return;
        }

        $sessionName = session_name();

        if (false === $sessionName) {
            return;
        }

        if (!isset($_COOKIE[$sessionName])) {
            return;
        }

        $params = session_get_cookie_params();

        setcookie(
            $sessionName,
            '',
            0,
            $params['path'],
            $params['domain'],
            $params['secure'],
            isset($params['httponly'])
        );
    }

    /**
     * @inheritDoc
     */
    public function save(string $data): void
    {
        $this->startSession();
        $_SESSION[self::class] = $data;
        session_write_close();
    }
}
