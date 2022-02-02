<?php

declare(strict_types=1);

namespace CliffordVickrey\MoyersBiggs\App\Controller;

use CliffordVickrey\MoyersBiggs\App\Route\Route;
use CliffordVickrey\MoyersBiggs\Domain\Entity\Event;
use CliffordVickrey\MoyersBiggs\Domain\Entity\State;
use CliffordVickrey\MoyersBiggs\Domain\Repository\EventRepositoryInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Clock\Clock;
use CliffordVickrey\MoyersBiggs\Infrastructure\Controller\ControllerInterface;
use CliffordVickrey\MoyersBiggs\Infrastructure\Http\HttpRequest;
use CliffordVickrey\MoyersBiggs\Infrastructure\UniqueId\UniqueId;
use CliffordVickrey\MoyersBiggs\Infrastructure\View\ViewModel;
use JetBrains\PhpStorm\Pure;

/**
 * Logs the questionnaire results
 */
final class SaveEventController implements ControllerInterface
{
    private Clock $clock;
    private EventRepositoryInterface $eventRepository;
    private UniqueId $uniqueId;

    /**
     * @param EventRepositoryInterface $eventRepository
     * @param Clock|null $clock
     * @param UniqueId|null $uniqueId
     */
    #[Pure]
    public function __construct(
        EventRepositoryInterface $eventRepository,
        ?Clock $clock = null,
        ?UniqueId $uniqueId = null
    ) {
        $this->clock = $clock ?? new Clock();
        $this->eventRepository = $eventRepository;
        $this->uniqueId = $uniqueId ?? new UniqueId();
    }

    /**
     * @inheritDoc
     */
    public function dispatch(HttpRequest $request): ViewModel
    {
        $state = $request->getEntity(State::class);

        $event = new Event(
            $this->uniqueId->getId(),
            $state->getQuestionnaireId(),
            $state->getStartTime(),
            $this->clock->now(),
            ...$state->toArray()
        );

        $this->eventRepository->save($event);

        $viewModel = new ViewModel();
        $viewModel->setRouteTo(Route::ROUTE_SAVE_FREQUENCIES);
        return $viewModel;
    }
}
