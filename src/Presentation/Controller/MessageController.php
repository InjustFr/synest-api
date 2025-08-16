<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Core\Application\Query\FindAllMessagesForChannelQueryInterface;
use App\Core\Application\Repository\MessageRepositoryInterface;
use App\Core\Domain\DTO\MessageDTO;
use App\Core\Domain\Entity\Channel;
use App\Core\Domain\Entity\Message;
use App\Presentation\Security\Model\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/servers/{server}/channels/{channel}/messages', name: 'api_servers_channels_messages_')]
final class MessageController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function listMessage(
        Channel $channel,
        FindAllMessagesForChannelQueryInterface $findAllMessagesForChannelQuery,
    ): Response {
        return $this->json(
            $findAllMessagesForChannelQuery->execute($channel),
            status: Response::HTTP_OK,
            context: ['groups' => 'message']
        );
    }

    #[Route('', name: 'add', methods: ['POST'])]
    public function addMessage(
        #[MapRequestPayload]
        MessageDTO $messageDTO,
        Channel $channel,
        #[CurrentUser]
        ?User $user,
        MessageRepositoryInterface $messageRepository,
    ): Response {
        $message = Message::create(
            $messageDTO->content,
            $user?->entity->getUsername() ?? '',
            $channel
        );

        $messageRepository->save($message);

        return $this->json(
            $message,
            status: Response::HTTP_CREATED,
            context: ['groups' => 'message']
        );
    }

    #[Route('', name: 'delete', methods: ['DELETE'])]
    public function removeMessage(
        Message $message,
        MessageRepositoryInterface $messageRepository,
    ): Response {
        $messageRepository->delete($message);

        return $this->json([], status: Response::HTTP_NO_CONTENT);
    }
}
