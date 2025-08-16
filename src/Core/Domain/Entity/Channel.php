<?php

declare(strict_types=1);

namespace App\Core\Domain\Entity;

use App\Core\Domain\Event\ChannelCreatedEvent;
use App\Core\Domain\Shared\ChannelType;
use App\Core\Domain\Shared\ContainsEventsInterface;
use App\Core\Domain\Shared\PrivateEventRecorderTrait;
use App\Core\Domain\Shared\RecordsEventsInterface;
use App\Infrastructure\Doctrine\Type\NonEmptyStringType;
use Assert\Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
class Channel implements RecordsEventsInterface, ContainsEventsInterface
{
    use PrivateEventRecorderTrait;

    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME)]
    private Ulid $id;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: NonEmptyStringType::TYPE)]
    private string $name;

    #[ORM\Column(type: NonEmptyStringType::TYPE, enumType: ChannelType::class)]
    private ChannelType $type;

    #[ORM\ManyToOne(Server::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Server $server;

    private function __construct(
        string $name,
        ChannelType $type,
        Server $server,
    ) {
        Assert::that($name)->notBlank('Name can not be blank');
        Assert::that($name)->maxLength(255, 'Name is too long');

        $this->id = new Ulid();
        $this->name = $name;
        $this->type = $type;
        $this->server = $server;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function rename(string $name): void
    {
        Assert::that($name)->notBlank('Name can not be blank');
        Assert::that($name)->maxLength(255, 'Name is too long');

        $this->name = $name;
    }

    public function getType(): ChannelType
    {
        return $this->type;
    }

    public function getServer(): Server
    {
        return $this->server;
    }

    public static function create(
        string $name,
        ChannelType $type,
        Server $server,
    ): self {
        $self = new self($name, $type, $server);

        $self->record(new ChannelCreatedEvent($self->id, $self->name, $self->type, $self->server->getId()));

        return $self;
    }
}
