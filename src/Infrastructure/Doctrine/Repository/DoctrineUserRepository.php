<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Core\Application\Repository\UserRepositoryInterface;
use App\Core\Domain\Entity\User;
use Assert\Assert;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use Symfony\Component\Uid\Ulid;

final class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return User[]
     */
    public function list(): array
    {
        $users = $this->entityManager->createQueryBuilder()
            ->select('c')
            ->from(User::class, 'c')
            ->getQuery()
            ->getResult();

        Assert::that($users)
            ->all()
            ->isInstanceOf(User::class, \sprintf('Returned array is not composed of only %s', User::class));

        return iterator_to_array($users);
    }

    public function get(Ulid $id): User
    {
        $user = $this->entityManager->find(User::class, $id);

        Assert::that($user)
            ->isInstanceOf(User::class, \sprintf('Could not find user for ULID %s', $id));
        Assert::that($user->getId())
            ->eq($id, \sprintf('Wrong object returned for class %s', User::class));

        return $user;
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        Assert::that($this->entityManager->getUnitOfWork()->getEntityState($user))
            ->eq(
                UnitOfWork::STATE_MANAGED,
                \sprintf('Could not persist %s with id %s', User::class, $user->getId())
            );
    }

    public function delete(User $user): void
    {
        $this->entityManager->remove($user);
        Assert::that($this->entityManager->getUnitOfWork()->getEntityState($user))
            ->eq(
                UnitOfWork::STATE_REMOVED,
                \sprintf('Could not remove %s with id %s', User::class, $user->getId())
            );
    }
}
