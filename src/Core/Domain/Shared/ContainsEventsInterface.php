<?php

declare(strict_types=1);

namespace App\Core\Domain\Shared;

interface ContainsEventsInterface
{
    /**
     * @return EventInterface[]
     */
    public function getRecordedEvents(): array;

    public function clearRecordedEvents(): void;
}
