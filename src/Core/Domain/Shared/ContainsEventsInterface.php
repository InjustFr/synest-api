<?php

namespace App\Core\Domain\Shared;

interface ContainsEventsInterface {
    /**
     * @return EventInterface[]
     */
    public function getRecordedEvents(): array;

    public function clearRecordedEvents(): void;
}