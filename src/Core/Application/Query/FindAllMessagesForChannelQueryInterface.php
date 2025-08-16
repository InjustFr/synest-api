<?php

declare(strict_types=1);

namespace App\Core\Application\Query;

use App\Core\Domain\Entity\Channel;
use App\Core\Domain\Entity\Message;

interface FindAllMessagesForChannelQueryInterface
{
    /** @return iterable<Message> */
    public function execute(Channel $channel): iterable;
}
