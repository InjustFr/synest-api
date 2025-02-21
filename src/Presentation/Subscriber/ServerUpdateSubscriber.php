<?php

declare(strict_types=1);

namespace App\Presentation\Subscriber;

use App\Core\Domain\Event\ChannelCreatedEvent;
use App\Core\Domain\Event\ChannelDeletedEvent;
use App\Core\Domain\Event\MessageCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

final class ServerUpdateSubscriber implements EventSubscriberInterface
{
    public function __construct(private HubInterface $hub)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            MessageCreatedEvent::class => ['onMessageCreated', 10],
            ChannelCreatedEvent::class => ['onChannelCreated', 10],
            ChannelDeletedEvent::class => ['onChannelDeleted', 10],
        ];
    }

    public function onMessageCreated(MessageCreatedEvent $messageCreatedEvent): void
    {
        $update = new Update(
            '/server/'.$messageCreatedEvent->server,
            json_encode([
                'type' => 'message_created',
                'data' => $messageCreatedEvent,
            ]) ?: ''
        );

        $this->hub->publish($update);
    }

    public function onChannelCreated(ChannelCreatedEvent $channelCreatedEvent): void
    {
        $update = new Update(
            '/server/'.$channelCreatedEvent->server,
            json_encode([
                'type' => 'channel_created',
                'data' => $channelCreatedEvent,
            ]) ?: ''
        );

        $this->hub->publish($update);
    }

    public function onChannelDeleted(ChannelDeletedEvent $channelDeletedEvent): void
    {
        $update = new Update(
            '/server/'.$channelDeletedEvent->server,
            json_encode([
                'type' => 'channel_deleted',
                'data' => $channelDeletedEvent,
            ]) ?: ''
        );

        $this->hub->publish($update);
    }
}
