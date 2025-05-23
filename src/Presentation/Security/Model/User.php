<?php

declare(strict_types=1);

namespace App\Presentation\Security\Model;

use App\Core\Domain\Entity\User as EntityUser;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    private function __construct(public readonly EntityUser $entity)
    {
    }

    #[\Override]
    public function getRoles(): array
    {
        return $this->entity->getRoles();
    }

    #[\Override]
    public function getUserIdentifier(): string
    {
        return $this->entity->getEmail();
    }

    #[\Override]
    public function getPassword(): string
    {
        return $this->entity->getPassword();
    }

    #[\Override]
    public function eraseCredentials(): void
    {
    }

    public static function createFromEntity(
        EntityUser $entity,
    ): self {
        return new self($entity);
    }
}
