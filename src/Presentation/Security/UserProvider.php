<?php

namespace App\Presentation\Security;

use App\Core\Application\Query\FindUserByEmailInterface;
use App\Presentation\Security\Model\User;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UserProvider implements UserProviderInterface
{
    public function __construct(private FindUserByEmailInterface $findUserByEmail)
    {
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $entityUser = $this->findUserByEmail->execute($identifier);

        if (!$entityUser) {
            throw new UserNotFoundException('User with identifier '.$identifier.' could not be found.');
        }

        return User::createFromEntity($entityUser);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        throw new \LogicException('Can not refresh user in stateless app');
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }
}
