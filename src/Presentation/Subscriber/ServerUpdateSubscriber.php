<?php

namespace App\Presentation\Subscriber;

use App\Core\Domain\Event\MessageCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

final class ServerUpdateSubscriber implements EventSubscriberInterface {
    public function __construct(private HubInterface $hub) {

    }

    public static function getSubscribedEvents()
    {
        return [
            MessageCreatedEvent::class => ['onMessageCreated', 10]
        ];
    }

    public function onMessageCreated(MessageCreatedEvent $messageCreatedEvent) {
        $update = new Update(
            '/server',
            json_encode([
                'type' => 'message_created',
                'data' => $messageCreatedEvent
            ])
        );
        
        $this->hub->publish($update);
    }
}