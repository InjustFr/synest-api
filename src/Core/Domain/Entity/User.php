<?php

namespace App\Core\Domain\Entity;

use Assert\Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME)]
    private Ulid $id;

    #[ORM\Column]
    private string $username;

    /**
     * @var non-empty-string
     */
    #[ORM\Column]
    private string $email;

    #[ORM\Column]
    private string $password;

    /**
     * @var string[]
     */
    #[ORM\Column('json')]
    private array $roles = ['ROLE_USER'];

    /**
     * @param non-empty-string $email
     */
    private function __construct(
        string $email,
        string $username,
        string $password,
    ) {
        Assert::that($username)->notBlank('Username can not be blank');
        Assert::that($email)->notBlank('Email can not be blank')->email('Email has wrong format');
        Assert::that($password)->notBlank('Password can not be blank');

        $this->id = new Ulid();
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        Assert::that($username)->notBlank('Username can not be blank');

        $this->username = $username;
    }

    /**
     * @return non-empty-string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param non-empty-string $email
     */
    public function setEmail(string $email): void
    {
        Assert::that($email)->notBlank('Email can not be blank')->email('Email has wrong format');

        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        Assert::that($password)->notBlank('Password can not be blank');

        $this->password = $password;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param non-empty-string $email
     */
    public static function create(
        string $email,
        string $username,
        string $password,
    ): self {
        return new self($email, $username, $password);
    }
}
