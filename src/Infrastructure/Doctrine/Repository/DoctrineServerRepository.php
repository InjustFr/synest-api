<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Core\Application\Repository\ServerRepositoryInterface;
use App\Core\Domain\Entity\Server;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Uid\Ulid;

final class DoctrineServerRepository implements ServerRepositoryInterface
{
    /**
     * @var ObjectRepository<Server>
     */
    private ObjectRepository $objectRepository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->objectRepository = $entityManager->getRepository(Server::class);
    }

    /**
     * @return Server[]
     */
    public function list(): array
    {
        return $this->objectRepository->findAll();
    }

    public function get(Ulid $id): Server
    {
        $server = $this->objectRepository->find($id);

        if (!$server) {
            throw new \InvalidArgumentException('ULID '.$id.' is not a valid server ULID');
        }

        return $server;
    }

    public function save(Server $server): void
    {
        $this->entityManager->persist($server);
    }

    public function delete(Server $server): void
    {
        $this->entityManager->remove($server);
    }
}
