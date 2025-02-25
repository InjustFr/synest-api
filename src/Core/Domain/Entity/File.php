<?php

declare(strict_types=1);

namespace App\Core\Domain\Entity;

use App\Infrastructure\Doctrine\Type\NonEmptyStringType;
use Assert\Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
class File
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME)]
    private Ulid $id;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(type: NonEmptyStringType::TYPE)]
    private string $path;

    #[ORM\ManyToOne(User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    private function __construct(
        string $path,
        User $user,
    ) {
        Assert::that($path)->notBlank('Path can not be blank.');
        Assert::that($path)->maxLength(255, 'Path is too long.');

        $this->id = new Ulid();

        $this->path = $path;
        $this->user = $user;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public static function create(string $path, User $user): self
    {
        return new self($path, $user);
    }
}
