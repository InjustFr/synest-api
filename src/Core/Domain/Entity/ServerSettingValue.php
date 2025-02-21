<?php

declare(strict_types=1);

namespace App\Core\Domain\Entity;

use Assert\Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
class ServerSettingValue
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME)]
    private Ulid $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ServerSetting $serverSetting;

    #[ORM\ManyToOne(inversedBy: 'settingValues')]
    #[ORM\JoinColumn(nullable: false)]
    private Server $server;

    #[ORM\Column]
    private string $value;

    private function __construct(
        ServerSetting $serverSetting,
        Server $server,
        mixed $value,
    ) {
        Assert::that($value)->satisfy(function (mixed $value) use ($serverSetting) {
            return \gettype($value) === $serverSetting->getType();
        }, \sprintf('Value is not of type %s', $serverSetting->getType()));

        $this->id = new Ulid();
        $this->serverSetting = $serverSetting;
        $this->server = $server;
        $this->value = json_encode($value) ?: '';
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getServerSetting(): ServerSetting
    {
        return $this->serverSetting;
    }

    public function getServer(): Server
    {
        return $this->server;
    }

    public function getValue(): mixed
    {
        return json_decode($this->value);
    }

    public function setValue(mixed $value): void
    {
        Assert::that($value)->satisfy(function (mixed $value) {
            return \gettype($value) === $this->serverSetting->getType();
        }, \sprintf('Value is not of type %s', $this->serverSetting->getType()));

        $this->value = json_encode($value) ?: '';
    }

    public static function create(
        ServerSetting $serverSetting,
        Server $server,
        mixed $value,
    ): self {
        return new self($serverSetting, $server, $value);
    }
}
