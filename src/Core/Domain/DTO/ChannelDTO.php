<?php

namespace App\Core\Domain\DTO;

use App\Core\Domain\Shared\ChannelType;

final readonly class ChannelDTO
{
    public function __construct(
        public string $name,
        public ChannelType $type
    ) {
    }
}
