<?php

declare(strict_types=1);

namespace App\Core\Domain\Shared;

interface RecordsEventsInterface
{
    public function record(EventInterface $event): void;
}
