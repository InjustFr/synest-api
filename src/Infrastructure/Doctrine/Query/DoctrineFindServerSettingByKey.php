<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Query;

use App\Core\Application\Query\FindServerSettingByKeyInterface;
use App\Core\Domain\Entity\ServerSetting;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineFindServerSettingByKey implements FindServerSettingByKeyInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function execute(string $key): ?ServerSetting
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('ss')
            ->from(ServerSetting::class, 'ss')
            ->where('ss.key = :key')
            ->setParameter('key', $key);

        $serverSetting = $qb->getQuery()->getOneOrNullResult();

        if (null !== $serverSetting && !$serverSetting instanceof ServerSetting) {
            throw new \LogicException('Query did not return expected ServerSetting or null type.');
        }

        return $serverSetting;
    }
}
