<?php

namespace App\Core\Domain\DTO;

use Symfony\Component\Uid\Ulid;

final readonly class MessageDTO
{
    public function __construct(
        public string $content,
        public string $username,
        public Ulid $channel
    ) {
    }
}
