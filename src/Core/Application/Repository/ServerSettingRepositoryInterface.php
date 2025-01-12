<?php

declare(strict_types=1);

namespace App\Core\Application\Repository;

use App\Core\Domain\Entity\ServerSetting;
use Symfony\Component\Uid\Ulid;

/**
 * @api
 */
interface ServerSettingRepositoryInterface
{
    /**
     * @return ServerSetting[]
     */
    public function list(): array;

    public function get(Ulid $id): ServerSetting;

    public function save(ServerSetting $serverSetting): void;

    public function delete(ServerSetting $serverSetting): void;
}
