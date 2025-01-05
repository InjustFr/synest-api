<?php

declare(strict_types=1);

namespace App\Core\Domain\DTO;

final readonly class ServerDTO
{
    public function __construct(
        public string $name,
    ) {
    }
}
