<?php

namespace App\Presentation\Controller;

use App\Core\Application\Repository\ServerRepositoryInterface;
use App\Core\Domain\DTO\ServerDTO;
use App\Core\Domain\Entity\Server;
use App\Presentation\Security\Model\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/servers', 'api_servers_')]
final class ServerController extends AbstractController
{
    #[Route('', 'list', methods: ['GET'])]
    public function list(
        ServerRepositoryInterface $serverRepository
    ): Response {
        return $this->json($serverRepository->list(), Response::HTTP_OK, context: ['groups' => 'server']);
    }

    #[Route('', 'post', methods: ['POST'])]
    public function post(
        #[MapRequestPayload]
        ServerDTO $serverDTO,
        #[CurrentUser]
        ?User $user,
        ServerRepositoryInterface $serverRepository
    ): Response {
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $server = Server::create(
            $serverDTO->name,
            $user->entity
        );

        $server->addUser($user->entity);

        $serverRepository->save($server);

        return $this->json($server, Response::HTTP_CREATED, context: ['groups' => 'server']);
    }

    #[Route('/{server}/join', 'join', methods: ['POST'])]
    public function join(
        Server $server,
        #[CurrentUser]
        ?User $user,
        ServerRepositoryInterface $serverRepository
    ): Response {
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $server->addUser($user->entity);
        $serverRepository->save($server);

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    #[Route('/{server}/leave', 'leave', methods: ['POST'])]
    public function leave(
        Server $server,
        #[CurrentUser]
        ?User $user,
        ServerRepositoryInterface $serverRepository
    ): Response {
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $server->removeUser($user->entity);
        $serverRepository->save($server);

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    #[Route('/{server}', 'delete', methods: ['DELETE'])]
    public function delete(
        Server $server,
        #[CurrentUser]
        ?User $user,
        ServerRepositoryInterface $serverRepository
    ): Response {
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        if ($server->getOwner() === $user->entity) {
            $serverRepository->delete($server);
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
