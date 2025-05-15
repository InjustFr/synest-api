<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Core\Application\Repository\ChannelRepositoryInterface;
use App\Core\Domain\Entity\Channel;
use Assert\Assert;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use Symfony\Component\Uid\Ulid;

final class DoctrineChannelRepository implements ChannelRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return Channel[]
     */
    #[\Override]
    public function list(): array
    {
        $channels = $this->entityManager->createQueryBuilder()
            ->select('c')
            ->from(Channel::class, 'c')
            ->getQuery()
            ->getResult();

        Assert::that($channels)
            ->all()
            ->isInstanceOf(Channel::class, \sprintf('Returned array is not composed of only %s', Channel::class));

        return iterator_to_array($channels);
    }

    #[\Override]
    public function get(Ulid $id): Channel
    {
        $channel = $this->entityManager->find(Channel::class, $id);

        Assert::that($channel)
            ->isInstanceOf(Channel::class, \sprintf('Could not find channel for ULID %s', $id));
        Assert::that($channel->getId())
            ->eq($id, \sprintf('Wrong object returned for class %s', Channel::class));

        return $channel;
    }

    #[\Override]
    public function save(Channel $channel): void
    {
        $this->entityManager->persist($channel);
        Assert::that($this->entityManager->getUnitOfWork()->getEntityState($channel))
            ->eq(
                UnitOfWork::STATE_MANAGED,
                \sprintf('Could not persist %s with id %s', Channel::class, $channel->getId())
            );
    }

    #[\Override]
    public function delete(Channel $channel): void
    {
        $this->entityManager->remove($channel);
        Assert::that($this->entityManager->getUnitOfWork()->getEntityState($channel))
            ->eq(
                UnitOfWork::STATE_REMOVED,
                \sprintf('Could not remove %s with id %s', Channel::class, $channel->getId())
            );
    }
}
