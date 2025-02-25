<?php

declare(strict_types=1);

namespace App\Core\Domain\Event;

use App\Core\Domain\Shared\EventInterface;
use Assert\Assert;
use Symfony\Component\Uid\Ulid;

final readonly class MessageCreatedEvent implements EventInterface
{
    public function __construct(
        public Ulid $id,
        public string $content,
        public string $username,
        public Ulid $channel,
        public Ulid $server,
    ) {
        Assert::that($content)
            ->notBlank('Name can not be blank');
        Assert::that($username)
            ->notBlank('Name can not be blank');
    }
}
