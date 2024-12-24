<?php

namespace App\Presentation\Controller;

use App\Core\Application\Repository\ChannelRepositoryInterface;
use App\Core\Application\Repository\ServerRepositoryInterface;
use App\Core\Domain\DTO\ChannelDTO;
use App\Core\Domain\Entity\Channel;
use App\Core\Domain\Event\ChannelDeletedEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/channels', name: 'api_channels_')]
final class ChannelController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function listChannel(
        ChannelRepositoryInterface $channelRepository,
        SerializerInterface $serializer,
    ): Response {
        return new JsonResponse($serializer->serialize($channelRepository->list(), 'json', ['groups' => 'channel']), status: Response::HTTP_OK, json: true);
    }

    #[Route('', name: 'add', methods: ['POST'])]
    public function addChannel(
        #[MapRequestPayload]
        ChannelDTO $channelDTO,
        ChannelRepositoryInterface $channelRepository,
        ServerRepositoryInterface $serverRepository,
        SerializerInterface $serializer,
    ): Response {
        $server = $serverRepository->get($channelDTO->server);

        $channel = Channel::create($channelDTO->name, $channelDTO->type, $server);
        $channelRepository->save($channel);

        return new JsonResponse($serializer->serialize($channel, 'json', ['groups' => 'channel']), status: Response::HTTP_CREATED, json: true);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function removeChannel(
        Channel $channel,
        ChannelRepositoryInterface $channelRepository,
    ): Response {
        $channelRepository->delete($channel);
        $channel->record(new ChannelDeletedEvent($channel->getId()));

        return new JsonResponse([], status: Response::HTTP_NO_CONTENT);
    }
}
