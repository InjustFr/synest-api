<?php

namespace App\Core\Domain\Shared;

interface RecordsEventsInterface
{
    public function record(EventInterface $event): void;
}
