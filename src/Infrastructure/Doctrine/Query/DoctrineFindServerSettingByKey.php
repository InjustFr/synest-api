<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Query;

use App\Core\Application\Query\FindServerSettingByKeyInterface;
use App\Core\Domain\Entity\ServerSetting;
use Assert\Assert;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineFindServerSettingByKey implements FindServerSettingByKeyInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function execute(string $key): ?ServerSetting
    {
        Assert::that($key)->notBlank('Key can not be blank');

        $qb = $this->entityManager->createQueryBuilder()
            ->select('ss')
            ->from(ServerSetting::class, 'ss')
            ->where('ss.key = :key')
            ->setParameter('key', $key);

        $serverSetting = $qb->getQuery()->getOneOrNullResult();

        Assert::that($serverSetting)
            ->nullOr()
            ->isInstanceOf(ServerSetting::class, 'Query did not return expected ServerSetting or null type.');

        return $serverSetting;
    }
}
