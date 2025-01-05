<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Core\Application\Repository\UserRepositoryInterface;
use App\Core\Domain\DTO\UserDTO;
use App\Core\Domain\Entity\User as EntityUser;
use App\Presentation\Security\Model\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'api_register')]
    public function register(
        #[MapRequestPayload]
        UserDTO $userDTO,
        UserPasswordHasherInterface $userPasswordHasher,
        UserRepositoryInterface $userRepository,
    ): Response {
        $userEntity = EntityUser::create($userDTO->email, $userDTO->username, 'temp');
        $user = User::createFromEntity($userEntity);

        $userEntity->setPassword($userPasswordHasher->hashPassword($user, $userDTO->password));

        $userRepository->save($userEntity);

        return $this->json([
            'email' => $userEntity->getEmail(),
            'username' => $userEntity->getUsername(),
        ], Response::HTTP_CREATED);
    }
}
