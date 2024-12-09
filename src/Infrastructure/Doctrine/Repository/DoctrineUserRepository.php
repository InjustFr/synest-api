<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Core\Application\Repository\UserRepositoryInterface;
use App\Core\Domain\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Uid\Ulid;

final class DoctrineUserRepository implements UserRepositoryInterface
{
    private ObjectRepository $objectRepository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->objectRepository = $entityManager->getRepository(User::class);
    }

    /**
     * @return User[]
     */
    public function list(): array
    {
        return $this->objectRepository->findAll();
    }

    public function get(Ulid $id): User
    {
        $user = $this->objectRepository->find($id);

        if (!$user) {
            throw new \InvalidArgumentException('ULID '.$id.' is not a valid user ULID');
        }

        return $user;
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
    }

    public function delete(User $user): void
    {
        $this->entityManager->remove($user);
    }
}
