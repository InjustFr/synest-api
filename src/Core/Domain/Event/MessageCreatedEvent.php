<?php

namespace App\Core\Domain\Event;

use App\Core\Domain\Shared\EventInterface;
use Symfony\Component\Uid\Ulid;

final readonly class MessageCreatedEvent implements EventInterface
{
    public function __construct(
        public Ulid $id,
        public string $content,
        public string $username,
        public Ulid $channel
    ) {
    }
}
