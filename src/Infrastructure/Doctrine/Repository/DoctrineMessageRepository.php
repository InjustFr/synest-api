<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Core\Application\Repository\MessageRepositoryInterface;
use App\Core\Domain\Entity\Message;
use Assert\Assert;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use Symfony\Component\Uid\Ulid;

final class DoctrineMessageRepository implements MessageRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return Message[]
     */
    #[\Override]
    public function list(): array
    {
        $messages = $this->entityManager->createQueryBuilder()
            ->select('c')
            ->from(Message::class, 'c')
            ->getQuery()
            ->getResult();

        Assert::that($messages)
            ->all()
            ->isInstanceOf(Message::class, \sprintf('Returned array is not composed of only %s', Message::class));

        return iterator_to_array($messages);
    }

    #[\Override]
    public function get(Ulid $id): Message
    {
        $message = $this->entityManager->find(Message::class, $id);

        Assert::that($message)
            ->isInstanceOf(Message::class, \sprintf('Could not find message for ULID %s', $id));
        Assert::that($message->getId())
            ->eq($id, \sprintf('Wrong object returned for class %s', Message::class));

        return $message;
    }

    #[\Override]
    public function save(Message $message): void
    {
        $this->entityManager->persist($message);
        Assert::that($this->entityManager->getUnitOfWork()->getEntityState($message))
            ->eq(
                UnitOfWork::STATE_MANAGED,
                \sprintf('Could not persist %s with id %s', Message::class, $message->getId())
            );
    }

    #[\Override]
    public function delete(Message $message): void
    {
        $this->entityManager->remove($message);
        Assert::that($this->entityManager->getUnitOfWork()->getEntityState($message))
            ->eq(
                UnitOfWork::STATE_REMOVED,
                \sprintf('Could not remove %s with id %s', Message::class, $message->getId())
            );
    }
}
