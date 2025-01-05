<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Query;

use App\Core\Application\Query\FindUserByEmailInterface;
use App\Core\Domain\Entity\User;
use Assert\Assert;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineFindUserByEmail implements FindUserByEmailInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function execute(string $email): ?User
    {
        Assert::that($email)->email('Email is not valid');

        $qb = $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email);

        $user = $qb->getQuery()->getOneOrNullResult();

        if (null !== $user && !$user instanceof User) {
            throw new \LogicException('Query did not return expected User or null type.');
        }

        return $user;
    }
}
