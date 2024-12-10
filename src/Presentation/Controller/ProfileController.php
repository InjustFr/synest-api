<?php

namespace App\Presentation\Controller;

use App\Presentation\Security\Model\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class ProfileController extends AbstractController
{
    #[Route('/profile', 'app_profile', methods: ['GET'])]
    public function profile(
        #[CurrentUser]
        ?User $user
    ): Response {
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        return $this->json($user->entity, context: [
            'groups' => 'user',
        ]);
    }
}
