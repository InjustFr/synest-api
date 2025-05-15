<?php

declare(strict_types=1);

namespace App\Presentation\Security;

use App\Core\Application\Query\FindUserByEmailInterface;
use App\Presentation\Security\Model\User;
use Assert\Assert;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @implements UserProviderInterface<User>
 */
final class UserProvider implements UserProviderInterface
{
    public function __construct(private FindUserByEmailInterface $findUserByEmail)
    {
    }

    #[\Override]
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        Assert::that($identifier)->email('Identifier must be a valid email.');

        $entityUser = $this->findUserByEmail->execute($identifier);

        if (null === $entityUser) {
            throw new UserNotFoundException(\sprintf('User with identifier %s could not be found.', $identifier));
        }

        return User::createFromEntity($entityUser);
    }

    #[\Override]
    public function refreshUser(UserInterface $user): UserInterface
    {
        throw new \LogicException('Can not refresh user in stateless app');
    }

    #[\Override]
    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }
}
