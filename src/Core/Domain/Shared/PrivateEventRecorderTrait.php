<?php

namespace App\Core\Domain\Shared;

trait PrivateEventRecorderTrait {
    /**
     * @var EventInterface[]
     */
    private array $events = [];

    /**
     * @return EventInterface[]
     */
    public function getRecordedEvents(): array {
        return $this->events;
    }

    public function clearRecordedEvents(): void {
        $this->events = [];
    }

    public function record(EventInterface $event): void {
        $this->events[] = $event;
    }
}