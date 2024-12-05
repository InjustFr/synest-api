<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Core\Application\Repository\ChannelRepositoryInterface;
use Symfony\Component\Uid\Ulid;
use App\Core\Domain\Entity\Channel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

final class DoctrineChannelRepository implements ChannelRepositoryInterface {
    private ObjectRepository $objectRepository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->objectRepository = $entityManager->getRepository(Channel::class);
    }

    /**
     * @return Channel[]
     */
    public function list(): array
    {
        return $this->objectRepository->findAll();
    }

    public function get(Ulid $id): Channel
    {
        $channel = $this->objectRepository->find($id);

        if(!$channel) {
            throw new \InvalidArgumentException('ULID '.$id.' is not a valid channel ULID');
        }

        return $channel;
    }

    public function save(Channel $channel): void
    {
        $this->entityManager->persist($channel);
    }

    public function delete(Channel $channel): void
    {
        $this->entityManager->remove($channel);
    }
}