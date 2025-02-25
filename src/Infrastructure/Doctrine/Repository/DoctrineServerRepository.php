<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Core\Application\Repository\ServerRepositoryInterface;
use App\Core\Domain\Entity\Server;
use Assert\Assert;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use Symfony\Component\Uid\Ulid;

final class DoctrineServerRepository implements ServerRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return Server[]
     */
    public function list(): array
    {
        $servers = $this->entityManager->createQueryBuilder()
            ->select('c')
            ->from(Server::class, 'c')
            ->getQuery()
            ->getResult();

        Assert::that($servers)
            ->all()
            ->isInstanceOf(Server::class, \sprintf('Returned array is not composed of only %s', Server::class));

        return iterator_to_array($servers);
    }

    public function get(Ulid $id): Server
    {
        $server = $this->entityManager->find(Server::class, $id);

        Assert::that($server)
            ->isInstanceOf(Server::class, \sprintf('Could not find server for ULID %s', $id));
        Assert::that($server->getId())
            ->eq($id, \sprintf('Wrong object returned for class %s', Server::class));

        return $server;
    }

    public function save(Server $server): void
    {
        $this->entityManager->persist($server);
        Assert::that($this->entityManager->getUnitOfWork()->getEntityState($server))
            ->eq(
                UnitOfWork::STATE_MANAGED,
                \sprintf('Could not persist %s with id %s', Server::class, $server->getId())
            );
    }

    public function delete(Server $server): void
    {
        $this->entityManager->remove($server);
        Assert::that($this->entityManager->getUnitOfWork()->getEntityState($server))
            ->eq(
                UnitOfWork::STATE_REMOVED,
                \sprintf('Could not remove %s with id %s', Server::class, $server->getId())
            );
    }
}
