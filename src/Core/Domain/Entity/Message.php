<?php

namespace App\Core\Domain\Entity;

use App\Core\Domain\Event\MessageCreatedEvent;
use App\Core\Domain\Shared\ContainsEventsInterface;
use App\Core\Domain\Shared\PrivateEventRecorderTrait;
use App\Core\Domain\Shared\RecordsEventsInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;
use Assert\Assert;
use Symfony\Bridge\Doctrine\Types\UlidType;

#[ORM\Entity]
class Message implements RecordsEventsInterface, ContainsEventsInterface {
    use PrivateEventRecorderTrait;

    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME)]
    public readonly Ulid $id;

    #[ORM\Column(type: 'text')]
    public string $content {
        set(string $value) {
            Assert::that($value)->notBlank('Content can not be blank');
            $this->content = $value;
        }
    }

    #[ORM\Column]
    public string $username {
        set(string $value) {
            Assert::that($value)->notBlank('Username can not be blank');
            $this->username = $value;
        }
    }

    #[ORM\ManyToOne(Channel::class, inversedBy: 'messages')]
    public Channel $channel;

    private function __construct(
        string $content,
        string $username,
        Channel $channel
    ) {
        $this->id = new Ulid();
        $this->content = $content;
        $this->username = $username;
        $this->channel = $channel;
    }

    public static function create(string $content, string $username, Channel $channel): self {
        $self = new self($content, $username, $channel);

        $self->record(new MessageCreatedEvent($self->id, $content, $username, $channel->id));

        return $self;
    }
}