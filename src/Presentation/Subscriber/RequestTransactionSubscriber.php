<?php

namespace App\Presentation\Subscriber;

use App\Core\Application\TransactionServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final class RequestTransactionSubscriber implements EventSubscriberInterface
{
    public function __construct(private TransactionServiceInterface $transactionService)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['startTransaction', 10],
            KernelEvents::RESPONSE => ['commitTransaction', 10],
            KernelEvents::EXCEPTION => ['rollbackTransaction', 1],
        ];
    }

    public function startTransaction(): void
    {
        $this->transactionService->start();
    }

    public function commitTransaction(): void
    {
        $this->transactionService->commit();
    }

    public function rollbackTransaction(): void
    {
        $this->transactionService->rollback();
    }
}
