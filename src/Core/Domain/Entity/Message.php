<?php

declare(strict_types=1);

namespace App\Core\Domain\Entity;

use App\Core\Domain\Event\MessageCreatedEvent;
use App\Core\Domain\Shared\ContainsEventsInterface;
use App\Core\Domain\Shared\PrivateEventRecorderTrait;
use App\Core\Domain\Shared\RecordsEventsInterface;
use App\Infrastructure\Doctrine\Type\NonEmptyStringType;
use App\Infrastructure\Doctrine\Type\NonEmptyTextType;
use Assert\Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
class Message implements RecordsEventsInterface, ContainsEventsInterface
{
    use PrivateEventRecorderTrait;

    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME)]
    private Ulid $id;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: NonEmptyTextType::TYPE)]
    private string $content;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: NonEmptyStringType::TYPE)]
    private string $username;

    #[ORM\ManyToOne(Channel::class)]
    #[ORM\JoinColumn(nullable: false)]
    private readonly Channel $channel;

    private function __construct(
        string $content,
        string $username,
        Channel $channel,
    ) {
        Assert::that($content)->notBlank('Content can not be blank.');
        Assert::that($username)->notBlank('Username can not be blank.');
        Assert::that($username)->maxLength(255, 'Username is too long.');

        $this->id = new Ulid();
        $this->content = $content;
        $this->username = $username;
        $this->channel = $channel;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getChannel(): Channel
    {
        return $this->channel;
    }

    public static function create(string $content, string $username, Channel $channel): self
    {
        $self = new self($content, $username, $channel);

        $self->record(new MessageCreatedEvent($self->id, $content, $username, $channel->getId(), $channel->getServer()->getId()));

        return $self;
    }
}
