<?php

declare(strict_types=1);

namespace App\Core\Application\Query;

use App\Core\Domain\Entity\Channel;
use App\Core\Domain\Entity\Server;

interface FindAllChannelsForServerInterface
{
    /** @return list<Channel> */
    public function execute(Server $server): array;
}
