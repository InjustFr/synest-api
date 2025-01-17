<?php

declare(strict_types=1);

namespace App\Core\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
class File
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME)]
    private readonly Ulid $id;

    #[ORM\Column]
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
