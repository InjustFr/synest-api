<?php

namespace App\Core\Domain\DTO;

final readonly class ServerSettingDTO
{
    public function __construct(
        public string $key,
        public string $type,
        public string $description,
    ) {
    }
}
