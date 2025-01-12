<?php

declare(strict_types=1);

namespace App\Core\Domain\DTO;

final readonly class ServerSettingValueDTO
{
    public function __construct(
        public mixed $value,
    ) {
    }
}
