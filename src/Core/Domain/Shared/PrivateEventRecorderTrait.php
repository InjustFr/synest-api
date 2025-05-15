<?php

declare(strict_types=1);

namespace App\Core\Domain\Shared;

trait PrivateEventRecorderTrait
{
    /**
     * @var EventInterface[]
     */
    private array $events = [];

    /**
     * @return EventInterface[]
     */
    #[\Override]
    public function getRecordedEvents(): array
    {
        return $this->events;
    }

    #[\Override]
    public function clearRecordedEvents(): void
    {
        $this->events = [];
    }

    #[\Override]
    public function record(EventInterface $event): void
    {
        $this->events[] = $event;
    }
}
