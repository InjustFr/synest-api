<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Presentation\Security\Model\User;
use Assert\Assert;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Discovery;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/user', 'app_user_')]
final class ProfileController extends AbstractController
{
    #[Route('/profile', 'profile', methods: ['GET'])]
    public function profile(
        #[CurrentUser]
        ?User $user,
    ): Response {
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        return $this->json($user->entity, Response::HTTP_OK, context: [
            'groups' => 'user',
        ]);
    }

    #[Route('/servers', 'servers', methods: ['GET'])]
    public function servers(
        #[CurrentUser]
        ?User $user,
        Request $request,
        Discovery $discovery,
    ): Response {
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $discovery->addLink($request);

        Assert::that($request->attributes->get('_links', null))
            ->notNull('Discovery link could not be added');

        return $this->json($user->entity->getServers(), Response::HTTP_OK, context: ['groups' => 'profile-server']);
    }
}
