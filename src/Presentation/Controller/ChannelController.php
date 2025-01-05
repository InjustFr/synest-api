<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Core\Application\Repository\ServerRepositoryInterface;
use App\Core\Domain\DTO\ChannelDTO;
use App\Core\Domain\Entity\Channel;
use App\Core\Domain\Entity\Server;
use App\Core\Domain\Event\ChannelDeletedEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/servers/{server}/channels', name: 'api_servers_channels_')]
final class ChannelController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function listChannel(
        Server $server,
    ): Response {
        return $this->json($server->getChannels(), Response::HTTP_OK, context: ['groups' => 'channel']);
    }

    #[Route('', name: 'add', methods: ['POST'])]
    public function addChannel(
        Server $server,
        #[MapRequestPayload]
        ChannelDTO $channelDTO,
        ServerRepositoryInterface $serverRepository,
    ): Response {
        $channel = Channel::create($channelDTO->name, $channelDTO->type, $server);
        $server->addChannel($channel);

        $serverRepository->save($server);

        return $this->json($channel, Response::HTTP_OK, context: ['groups' => 'channel']);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function removeChannel(
        Server $server,
        Channel $channel,
        ServerRepositoryInterface $serverRepository,
    ): Response {
        $server->removeChannel($channel);

        $serverRepository->save($server);

        $channel->record(new ChannelDeletedEvent($channel->getId(), $server->getId()));

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
