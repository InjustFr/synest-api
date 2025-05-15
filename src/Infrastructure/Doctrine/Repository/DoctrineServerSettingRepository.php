<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Core\Application\Repository\ServerSettingRepositoryInterface;
use App\Core\Domain\Entity\ServerSetting;
use Assert\Assert;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use Symfony\Component\Uid\Ulid;

final class DoctrineServerSettingRepository implements ServerSettingRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return ServerSetting[]
     */
    #[\Override]
    public function list(): array
    {
        $serverSettings = $this->entityManager->createQueryBuilder()
            ->select('c')
            ->from(ServerSetting::class, 'c')
            ->getQuery()
            ->getResult();

        Assert::that($serverSettings)
            ->all()
            ->isInstanceOf(ServerSetting::class, \sprintf('Returned array is not composed of only %s', ServerSetting::class));

        return iterator_to_array($serverSettings);
    }

    #[\Override]
    public function get(Ulid $id): ServerSetting
    {
        $serverSetting = $this->entityManager->find(ServerSetting::class, $id);

        Assert::that($serverSetting)
            ->isInstanceOf(ServerSetting::class, \sprintf('Could not find serverSetting for ULID %s', $id));
        Assert::that($serverSetting->getId())
            ->eq($id, \sprintf('Wrong object returned for class %s', ServerSetting::class));

        return $serverSetting;
    }

    #[\Override]
    public function save(ServerSetting $serverSetting): void
    {
        $this->entityManager->persist($serverSetting);
        Assert::that($this->entityManager->getUnitOfWork()->getEntityState($serverSetting))
            ->eq(
                UnitOfWork::STATE_MANAGED,
                \sprintf('Could not persist %s with id %s', ServerSetting::class, $serverSetting->getId())
            );
    }

    #[\Override]
    public function delete(ServerSetting $serverSetting): void
    {
        $this->entityManager->remove($serverSetting);
        Assert::that($this->entityManager->getUnitOfWork()->getEntityState($serverSetting))
            ->eq(
                UnitOfWork::STATE_REMOVED,
                \sprintf('Could not remove %s with id %s', ServerSetting::class, $serverSetting->getId())
            );
    }
}
