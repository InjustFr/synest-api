<?php

namespace App\Presentation\Controller;

use App\Core\Application\Repository\ChannelRepositoryInterface;
use App\Core\Application\Repository\MessageRepositoryInterface;
use App\Core\Domain\DTO\MessageDTO;
use App\Core\Domain\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/messages', name: 'api_messages_')]
final class MessageController extends AbstractController
{
    #[Route('/', name: 'list', methods: ['GET'])]
    public function listMessage(
        MessageRepositoryInterface $messageRepository,
        SerializerInterface $serializer
    ): Response {
        return new JsonResponse($serializer->serialize($messageRepository->list(), 'json', ['groups' => 'message']), status: Response::HTTP_OK, json: true);
    }

    #[Route('', name: 'add', methods: ['POST'])]
    public function addMessage(
        #[MapRequestPayload]
        MessageDTO $messageDTO,
        MessageRepositoryInterface $messageRepository,
        ChannelRepositoryInterface $channelRepository,
        SerializerInterface $serializer,
    ): Response {
        $message = Message::create($messageDTO->content, $messageDTO->username, $channelRepository->get($messageDTO->channel));
        $messageRepository->save($message);

        return new JsonResponse($serializer->serialize($message, 'json', ['groups' => 'message']), status: Response::HTTP_CREATED, json: true);
    }

    #[Route('', name: 'delete', methods: ['DELETE'])]
    public function removeMessage(
        Message $message,
        MessageRepositoryInterface $messageRepository,
    ) {
        $messageRepository->delete($message);

        return new JsonResponse([], status: Response::HTTP_NO_CONTENT);
    }
}
