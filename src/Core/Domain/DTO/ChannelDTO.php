<?php

namespace App\Core\Domain\DTO;

use App\Core\Domain\Shared\ChannelType;
use Symfony\Component\Uid\Ulid;

final readonly class ChannelDTO
{
    public function __construct(
        public string $name,
        public ChannelType $type,
        public Ulid $server
    ) {
    }
}
