<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Core\Application\Repository\ChannelRepositoryInterface;
use App\Core\Application\Repository\MessageRepositoryInterface;
use App\Core\Domain\DTO\MessageDTO;
use App\Core\Domain\Entity\Message;
use Assert\Assert;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/messages', name: 'api_messages_')]
final class MessageController extends AbstractController
{
    #[Route('/', name: 'list', methods: ['GET'])]
    public function listMessage(
        MessageRepositoryInterface $messageRepository,
    ): Response {
        return $this->json(
            $messageRepository->list(),
            status: Response::HTTP_CREATED,
            context: ['groups' => 'message']
        );
    }

    #[Route('', name: 'add', methods: ['POST'])]
    public function addMessage(
        #[MapRequestPayload]
        MessageDTO $messageDTO,
        MessageRepositoryInterface $messageRepository,
        ChannelRepositoryInterface $channelRepository,
    ): Response {
        $channel = $channelRepository->get($messageDTO->channel);
        $message = Message::create($messageDTO->content, $messageDTO->username, $channel);

        Assert::that($message)
            ->inArray(
                $channel->getMessages(),
                \sprintf('Could not add new message in channel %s.', $channel->getId())
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
