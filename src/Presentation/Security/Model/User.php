<?php

namespace App\Presentation\Security\Model;

use App\Core\Domain\Entity\User as EntityUser;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    private function __construct(public EntityUser $entity)
    {
    }

    public function getRoles(): array
    {
        return $this->entity->roles;
    }

    public function getUserIdentifier(): string
    {
        return $this->entity->email;
    }

    public function getPassword(): ?string
    {
        return $this->entity->password;
    }

    public function eraseCredentials(): void
    {
    }

    public static function createFromEntity(
        EntityUser $entity
    ): self {
        return new self($entity);
    }
}
