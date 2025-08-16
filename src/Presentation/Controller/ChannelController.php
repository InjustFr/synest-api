<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Core\Application\Query\FindAllChannelsForServerInterface;
use App\Core\Application\Repository\ChannelRepositoryInterface;
use App\Core\Domain\DTO\ChannelDTO;
use App\Core\Domain\Entity\Channel;
use App\Core\Domain\Entity\Server;
use App\Core\Domain\Event\ChannelDeletedEvent;
use Assert\Assert;
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
        FindAllChannelsForServerInterface $findAllChannelsForServer,
    ): Response {

        return $this->json(
            $findAllChannelsForServer->execute($server),
            Response::HTTP_OK,
            context: ['groups' => 'channel']
        );
    }

    #[Route('', name: 'add', methods: ['POST'])]
    public function addChannel(
        Server $server,
        #[MapRequestPayload]
        ChannelDTO $channelDTO,
        ChannelRepositoryInterface $channelRepository,
    ): Response {
        $channel = Channel::create($channelDTO->name, $channelDTO->type, $server);

        $channelRepository->save($channel);

        return $this->json($channel, Response::HTTP_OK, context: ['groups' => 'channel']);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function removeChannel(
        Server $server,
        Channel $channel,
        ChannelRepositoryInterface $channelRepository,
    ): Response {
        $channelRepository->delete($channel);

        $event = new ChannelDeletedEvent($channel->getId(), $server->getId());
        $channel->record($event);

        Assert::that($event)->inArray($channel->getRecordedEvents(), 'Could not record delete event.');

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
