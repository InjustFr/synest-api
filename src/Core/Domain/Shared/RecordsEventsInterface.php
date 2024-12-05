<?php

namespace App\Core\Domain\Shared;

interface RecordsEventsInterface {
    /**
     * @return EventInterface[]
     */
    public function record(EventInterface $event): void;
}