<?php

namespace App\Core\Domain\Event;

use App\Core\Domain\Shared\ChannelType;
use App\Core\Domain\Shared\EventInterface;
use Symfony\Component\Uid\Ulid;

final readonly class ChannelCreatedEvent implements EventInterface
{
    public function __construct(
        public Ulid $id,
        public string $name,
        public ChannelType $type,
    ) {
    }
}
