<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Core\Application\Repository\MessageRepositoryInterface;
use App\Core\Domain\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Uid\Ulid;

final class DoctrineMessageRepository implements MessageRepositoryInterface
{
    private ObjectRepository $objectRepository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->objectRepository = $entityManager->getRepository(Message::class);
    }

    /**
     * @return Message[]
     */
    public function list(): array
    {
        return $this->objectRepository->findAll();
    }

    public function get(Ulid $id): Message
    {
        return $this->objectRepository->find($id);
    }

    public function save(Message $message): void
    {
        $this->entityManager->persist($message);
    }

    public function delete(Message $message): void
    {
        $this->entityManager->remove($message);
    }
}
