<?php

declare(strict_types=1);

namespace App\Core\Application;

interface TransactionServiceInterface
{
    public function start(): bool;

    public function commit(): bool;

    public function rollback(): bool;
}
