<?php

declare(strict_types=1);

namespace App\Core\Application\Repository;

use App\Core\Domain\Entity\Server;
use Symfony\Component\Uid\Ulid;

/**
 * @api
 */
interface ServerRepositoryInterface
{
    /**
     * @return Server[]
     */
    public function list(): array;

    public function get(Ulid $id): Server;

    public function save(Server $server): void;

    public function delete(Server $server): void;
}
