<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Core\Application\Repository\ServerSettingRepositoryInterface;
use App\Core\Domain\Entity\ServerSetting;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Uid\Ulid;

final class DoctrineServerSettingRepository implements ServerSettingRepositoryInterface
{
    /**
     * @var ObjectRepository<ServerSetting>
     */
    private ObjectRepository $objectRepository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->objectRepository = $entityManager->getRepository(ServerSetting::class);
    }

    /**
     * @return ServerSetting[]
     */
    public function list(): array
    {
        return $this->objectRepository->findAll();
    }

    public function get(Ulid $id): ServerSetting
    {
        $serversetting = $this->objectRepository->find($id);

        if (!$serversetting) {
            throw new \InvalidArgumentException('ULID '.$id.' is not a valid serversetting ULID');
        }

        return $serversetting;
    }

    public function save(ServerSetting $serverSetting): void
    {
        $this->entityManager->persist($serverSetting);
    }

    public function delete(ServerSetting $serverSetting): void
    {
        $this->entityManager->remove($serverSetting);
    }
}
