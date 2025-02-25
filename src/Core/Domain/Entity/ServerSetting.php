<?php

declare(strict_types=1);

namespace App\Core\Domain\Entity;

use App\Infrastructure\Doctrine\Type\NonEmptyStringType;
use App\Infrastructure\Doctrine\Type\NonEmptyTextType;
use Assert\Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
class ServerSetting
{
    private const array ALLOWED_TYPES = ['int', 'boolean', 'string', 'float'];

    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME)]
    private Ulid $id;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: NonEmptyStringType::TYPE)]
    private string $key;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: NonEmptyStringType::TYPE)]
    private string $type;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: NonEmptyTextType::TYPE)]
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
        Assert::that($key)->maxLength(255, 'Key can not be longer than 255 characters.');

        Assert::that($type)->notBlank('Type can not be blank.');
        Assert::that($type)->inArray(
            self::ALLOWED_TYPES,
            \sprintf('Type must be one of the following types: [%s]', implode(',', self::ALLOWED_TYPES))
        );

        Assert::that($description)->notBlank('Description can not be blank.');

        Assert::that($defaultValue)->scalar('Default value must be a scalar.');
        Assert::that(\gettype($defaultValue))->eq($type, \sprintf('Default value must be of type %s', $type));

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
        Assert::that($key)->maxLength(255, 'Key can not be longer than 255 characters.');

        $this->key = $key;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        Assert::that($type)->notBlank('Type can not be blank.');
        Assert::that($type)->inArray(
            self::ALLOWED_TYPES,
            \sprintf('Type must be one of the following types: [%s]', implode(',', self::ALLOWED_TYPES))
        );

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
        $defaultValue = json_decode($this->defaultValue, true);

        Assert::that(json_last_error())->eq(\JSON_ERROR_NONE, 'Could not decode JSON value.');
        Assert::that(\gettype($defaultValue))->eq($this->type, 'Decode value is not of type %s', $this->type);

        return $defaultValue;
    }

    public function setDefaultValue(mixed $defaultValue): void
    {
        Assert::that($defaultValue)->scalar('Default value must be a scalar.');
        Assert::that(\gettype($defaultValue))->eq(
            $this->type,
            \sprintf('Default value must be of type %s', $this->type)
        );

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
