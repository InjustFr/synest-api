<?php

namespace App\Core\Domain\DTO;

final readonly class UserDTO
{
    /**
     * @param non-empty-string $email
     */
    public function __construct(
        public string $username,
        public string $email,
        public string $password,
    ) {
    }
}
