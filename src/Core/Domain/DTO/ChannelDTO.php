<?php

namespace App\Core\Domain\DTO;

final readonly class ChannelDTO
{
    public function __construct(
        public string $name
    ) {
    }
}
