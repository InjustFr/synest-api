<?php

namespace App\Core\Application\Query;

use App\Core\Domain\Entity\User;

interface FindUserByEmailInterface
{
    public function execute(string $email): ?User;
}
