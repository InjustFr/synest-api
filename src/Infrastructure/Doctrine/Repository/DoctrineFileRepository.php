<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Core\Application\Repository\FileRepositoryInterface;
use App\Core\Domain\Entity\File;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Uid\Ulid;

final class DoctrineFileRepository implements FileRepositoryInterface
{
    /**
     * @var ObjectRepository<File>
     */
    private ObjectRepository $objectRepository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->objectRepository = $entityManager->getRepository(File::class);
    }

    /**
     * @return File[]
     */
    public function list(): array
    {
        return $this->objectRepository->findAll();
    }

    public function get(Ulid $id): File
    {
        $file = $this->objectRepository->find($id);

        if (!$file) {
            throw new \InvalidArgumentException('ULID '.$id.' is not a valid file ULID');
        }

        return $file;
    }

    public function save(File $file): void
    {
        $this->entityManager->persist($file);
    }

    public function delete(File $file): void
    {
        $this->entityManager->remove($file);
    }
}
