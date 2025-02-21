<?php

declare(strict_types=1);

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
    private Ulid $id;

    #[ORM\Column]
    private string $key;

    #[ORM\Column]
    private string $type;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column]
    private string $defaultValue;

    private function __construct(
        string $key,
        string $type,
        string $description,
        mixed $defaultValue,
    ) {
        Assert::that($key)->notBlank('Key can not be blank.');
        Assert::that($type)->notBlank('Type can not be blank.');
        Assert::that($description)->notBlank('Description can not be blank.');
        Assert::that($defaultValue)->satisfy(function (mixed $defaultValue) use ($type) {
            return \gettype($defaultValue) === $type;
        }, \sprintf('Default value is not of type %s', $type));

        $this->id = new Ulid();
        $this->key = $key;
        $this->type = $type;
        $this->description = $description;
        $this->defaultValue = json_encode($defaultValue) ?: '';
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

    public function getDefaultValue(): mixed
    {
        return json_decode($this->defaultValue);
    }

    public function setDefaultValue(mixed $defaultValue): void
    {
        Assert::that($defaultValue)->satisfy(function (mixed $defaultValue) {
            return \gettype($defaultValue) === $this->type;
        }, \sprintf('Default value is not of type %s', $this->type));

        $this->defaultValue = json_encode($defaultValue) ?: '';
    }

    public static function create(
        string $key,
        string $type,
        string $description,
        mixed $defaultValue,
    ): self {
        return new self($key, $type, $description, $defaultValue);
    }
}
