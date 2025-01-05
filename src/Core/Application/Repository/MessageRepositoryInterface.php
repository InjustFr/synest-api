<?php

declare(strict_types=1);

namespace App\Core\Application\Repository;

use App\Core\Domain\Entity\Message;
use Symfony\Component\Uid\Ulid;

/**
 * @api
 */
interface MessageRepositoryInterface
{
    /**
     * @return Message[]
     */
    public function list(): array;

    public function get(Ulid $id): Message;

    public function save(Message $message): void;

    public function delete(Message $message): void;
}
