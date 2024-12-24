<?php

namespace App\Core\Domain\DTO;

final readonly class ServerDTO
{
    public function __construct(
        public string $name
    ) {
    }
}
