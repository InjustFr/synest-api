<?php

declare(strict_types=1);

namespace App\Core\Application;

interface TransactionServiceInterface
{
    public function start(): void;

    public function commit(): void;

    public function rollback(): void;
}
