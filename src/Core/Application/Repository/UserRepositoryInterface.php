<?php

namespace App\Core\Application\Repository;

use App\Core\Domain\Entity\User;
use Symfony\Component\Uid\Ulid;

interface UserRepositoryInterface
{
    public function list(): array;

    public function get(Ulid $id): User;

    public function save(User $user): void;

    public function delete(User $user): void;
}
