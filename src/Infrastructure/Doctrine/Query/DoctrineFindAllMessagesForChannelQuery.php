<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Query;

use App\Core\Application\Query\FindAllMessagesForChannelQueryInterface;
use App\Core\Domain\Entity\Channel;
use App\Core\Domain\Entity\Message;
use App\Core\Domain\Shared\Assertion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Types\UlidType;

final class DoctrineFindAllMessagesForChannelQuery implements FindAllMessagesForChannelQueryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[\Override]
    public function execute(Channel $channel): iterable
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('m')
            ->from(Message::class, 'm')
            ->where('m.channel = :channel')
            ->setParameter('channel', $channel->getId(), UlidType::NAME);

        $iterator = $qb->getQuery()->toIterable();
        foreach ($iterator as $value) {
            Assertion::isInstanceOf($value, Message::class);

            yield $value;
        }
    }
}
