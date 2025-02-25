<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine;

use App\Core\Application\TransactionServiceInterface;
use Assert\Assert;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineTransactionService implements TransactionServiceInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function start(): bool
    {
        $result = $this->entityManager->getConnection()->beginTransaction();

        Assert::that($result)
            ->true('Could not start database transaction');
        Assert::that($this->entityManager->getConnection()->isTransactionActive())
            ->true('Could not start database transaction');

        return $result;
    }

    public function commit(): bool
    {
        $result = true;

        $this->entityManager->flush();

        if ($this->entityManager->getConnection()->isTransactionActive()) {
            $result = $this->entityManager->getConnection()->commit();

            Assert::that($result)
                ->true('Could not commit database transaction');
        }

        return $result;
    }

    public function rollback(): bool
    {
        $result = true;

        if ($this->entityManager->getConnection()->isTransactionActive()) {
            $result = $this->entityManager->getConnection()->rollBack();
            Assert::that($result)
                ->true('Could not rollback database transaction');
        }

        $this->entityManager->clear();

        return $result;
    }
}
