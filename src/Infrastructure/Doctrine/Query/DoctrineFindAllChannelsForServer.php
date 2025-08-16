<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Query;

use App\Core\Application\Query\FindAllChannelsForServerInterface;
use App\Core\Domain\Entity\Channel;
use App\Core\Domain\Entity\Server;
use App\Core\Domain\Shared\Assertion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Types\UlidType;

final class DoctrineFindAllChannelsForServer implements FindAllChannelsForServerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[\Override]
    public function execute(Server $server): array
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('c')
            ->from(Channel::class, 'c')
            ->where('c.server = :server')
            ->setParameter('server', $server->getId(), UlidType::NAME);

        $result = $qb->getQuery()->getResult();

        Assertion::isList($result, 'Could not fetch an array of channels');
        Assertion::allIsInstanceOf($result, Channel::class, 'Fetched objects of wrong type for channels');

        return $result;
    }
}
