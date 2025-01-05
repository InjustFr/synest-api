<?php

declare(strict_types=1);

namespace App\Core\Application\Repository;

use App\Core\Domain\Entity\Channel;
use Symfony\Component\Uid\Ulid;

/**
 * @api
 */
interface ChannelRepositoryInterface
{
    /**
     * @return Channel[]
     */
    public function list(): array;

    public function get(Ulid $id): Channel;

    public function save(Channel $channel): void;

    public function delete(Channel $channel): void;
}
