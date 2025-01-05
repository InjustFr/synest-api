<?php

namespace App\Core\Domain\Entity;

use Assert\Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
class ServerSetting
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME)]
    private readonly Ulid $id;

    #[ORM\Column]
    private string $key;

    #[ORM\Column]
    private string $type;

    #[ORM\Column(type: 'text')]
    private string $description;

    private function __construct(
        string $key,
        string $type,
        string $description,
    ) {
        Assert::that($key)->notBlank('Key can not be blank.');
        Assert::that($type)->notBlank('Type can not be blank.');
        Assert::that($description)->notBlank('Description can not be blank.');

        $this->id = new Ulid();
        $this->key = $key;
        $this->type = $type;
        $this->description = $description;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        Assert::that($key)->notBlank('Key can not be blank.');

        $this->key = $key;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        Assert::that($type)->notBlank('Type can not be blank.');

        $this->type = $type;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        Assert::that($description)->notBlank('Description can not be blank.');

        $this->description = $description;
    }

    public static function create(
        string $key,
        string $type,
        string $description,
    ): self {
        return new self($key, $type, $description);
    }
}
