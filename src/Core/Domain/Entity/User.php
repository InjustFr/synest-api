<?php

namespace App\Core\Domain\Entity;

use Assert\Assert;
use Symfony\Component\Uid\Ulid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;

#[ORM\Entity]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME)]
    public Ulid $id;

    #[ORM\Column]
    public string $username {
        set(string $value) {
            Assert::that($value)->notBlank('Username can not be blank');
            $this->username = $value;
        }
    }

    #[ORM\Column]
    public string $email {
        set(string $value) {
            Assert::that($value)->notBlank('Email can not be blank')->email('Email has wrong format');
            $this->email = $value;
        }
    }

    #[ORM\Column]
    public string $password {
        set(string $value) {
            Assert::that($value)->notBlank('Password can not be blank');
            $this->password = $value;
        }
    }

    #[ORM\Column('json')]
    public array $roles = ['ROLE_USER'];

    private function __construct(
        string $email,
        string $username,
        string $password
    ) {
        $this->id = new Ulid();
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
    }

    public static function create(
        string $email,
        string $username,
        string $password
    ): self {
        return new self($email, $username, $password);
    }
}
