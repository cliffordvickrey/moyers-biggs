<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\Domain\Repository;

use CliffordVickrey\MoyersBiggs\Domain\Entity\State;

interface StateRepositoryInterface
{
    /**
     * Fetch the user's state from $_SESSION
     * @return State
     */
    public function getState(): State;

    /**
     * Clear $_SESSION; expire session cookie
     * @return void
     */
    public function deleteState(): void;

    /**
     * Persist the session and call session_write_close()
     * @param State $state
     * @return void
     */
    public function saveSate(State $state): void;
}
