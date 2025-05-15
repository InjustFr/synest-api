<?php

declare(strict_types=1);

namespace App\Presentation\Subscriber;

use App\Core\Application\TransactionServiceInterface;
use Assert\Assert;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final class RequestTransactionSubscriber implements EventSubscriberInterface
{
    public function __construct(private TransactionServiceInterface $transactionService)
    {
    }

    #[\Override]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['startTransaction', 10],
            KernelEvents::RESPONSE => ['commitTransaction', 10],
            KernelEvents::EXCEPTION => ['rollbackTransaction', 1],
        ];
    }

    public function startTransaction(): void
    {
        $result = $this->transactionService->start();

        Assert::that($result)->true('Transaction could not be started.');
    }

    public function commitTransaction(): void
    {
        $result = $this->transactionService->commit();

        Assert::that($result)->true('Transaction could not be commited.');
    }

    public function rollbackTransaction(): void
    {
        $result = $this->transactionService->rollback();

        Assert::that($result)->true('Transaction could not be rollbacked.');
    }
}
