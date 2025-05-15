<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Core\Application\Repository\FileRepositoryInterface;
use App\Core\Domain\Entity\File;
use Assert\Assert;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use Symfony\Component\Uid\Ulid;

final class DoctrineFileRepository implements FileRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return File[]
     */
    #[\Override]
    public function list(): array
    {
        $files = $this->entityManager->createQueryBuilder()
            ->select('c')
            ->from(File::class, 'c')
            ->getQuery()
            ->getResult();

        Assert::that($files)
            ->all()
            ->isInstanceOf(File::class, \sprintf('Returned array is not composed of only %s', File::class));

        return iterator_to_array($files);
    }

    #[\Override]
    public function get(Ulid $id): File
    {
        $file = $this->entityManager->find(File::class, $id);

        Assert::that($file)
            ->isInstanceOf(File::class, \sprintf('Could not find file for ULID %s', $id));
        Assert::that($file->getId())
            ->eq($id, \sprintf('Wrong object returned for class %s', File::class));

        return $file;
    }

    #[\Override]
    public function save(File $file): void
    {
        $this->entityManager->persist($file);
        Assert::that($this->entityManager->getUnitOfWork()->getEntityState($file))
            ->eq(
                UnitOfWork::STATE_MANAGED,
                \sprintf('Could not persist %s with id %s', File::class, $file->getId())
            );
    }

    #[\Override]
    public function delete(File $file): void
    {
        $this->entityManager->remove($file);
        Assert::that($this->entityManager->getUnitOfWork()->getEntityState($file))
            ->eq(
                UnitOfWork::STATE_REMOVED,
                \sprintf('Could not remove %s with id %s', File::class, $file->getId())
            );
    }
}
