<?php

declare(strict_types=1);

namespace App\Presentation\Subscriber;

use App\Core\Domain\Event\ChannelCreatedEvent;
use App\Core\Domain\Event\ChannelDeletedEvent;
use App\Core\Domain\Event\MessageCreatedEvent;
use Assert\Assert;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

final class ServerUpdateSubscriber implements EventSubscriberInterface
{
    public function __construct(private HubInterface $hub)
    {
    }

    #[\Override]
    public static function getSubscribedEvents(): array
    {
        return [
            MessageCreatedEvent::class => ['onMessageCreated', 10],
            ChannelCreatedEvent::class => ['onChannelCreated', 10],
            ChannelDeletedEvent::class => ['onChannelDeleted', 10],
        ];
    }

    public function onMessageCreated(MessageCreatedEvent $messageCreatedEvent): void
    {
        $data = json_encode([
            'type' => 'message_created',
            'data' => $messageCreatedEvent,
        ]);

        Assert::that($data)->string('Could not format update payload.');

        $update = new Update(
            \sprintf('/server/%s', $messageCreatedEvent->server),
            $data
        );

        $this->hub->publish($update);
    }

    public function onChannelCreated(ChannelCreatedEvent $channelCreatedEvent): void
    {
        $data = json_encode([
            'type' => 'channel_created',
            'data' => $channelCreatedEvent,
        ]);

        Assert::that($data)->string('Could not format update payload.');

        $update = new Update(
            \sprintf('/server/%s', $channelCreatedEvent->server),
            $data
        );

        $this->hub->publish($update);
    }

    public function onChannelDeleted(ChannelDeletedEvent $channelDeletedEvent): void
    {
        $data = json_encode([
            'type' => 'channel_deleted',
            'data' => $channelDeletedEvent,
        ]);

        Assert::that($data)->string('Could not format update payload.');

        $update = new Update(
            \sprintf('/server/%s', $channelDeletedEvent->server),
            $data
        );

        $this->hub->publish($update);
    }
}
