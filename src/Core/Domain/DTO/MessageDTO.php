<?php

declare(strict_types=1);

namespace App\Core\Domain\DTO;

final readonly class MessageDTO
{
    public function __construct(
        public string $content,
    ) {
    }
}
