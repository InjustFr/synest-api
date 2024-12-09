<?php

namespace App\Core\Domain\DTO;

final readonly class UserDTO
{
    public function __construct(
        public string $username,
        public string $email,
        public string $password,
    ) {
    }
}
