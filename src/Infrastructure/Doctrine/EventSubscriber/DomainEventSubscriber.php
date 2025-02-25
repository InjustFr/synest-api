<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\EventSubscriber;

use App\Core\Domain\Shared\ContainsEventsInterface;
use App\Core\Domain\Shared\EventInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ObjectManager;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsDoctrineListener(event: Events::prePersist, priority: 500, connection: 'default')]
#[AsDoctrineListener(event: Events::preUpdate, priority: 500, connection: 'default')]
#[AsDoctrineListener(event: Events::preRemove, priority: 500, connection: 'default')]
#[AsDoctrineListener(event: Events::preFlush, priority: 500, connection: 'default')]
#[AsDoctrineListener(event: Events::postFlush, priority: 500, connection: 'default')]
final class DomainEventSubscriber
{
    /**
     * @var array<array-key, ContainsEventsInterface>
     */
    private array $entities = [];

    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $this->addContainsEventsEntityToCollection($args);
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $this->addContainsEventsEntityToCollection($args);
    }

    public function preRemove(PreRemoveEventArgs $args): void
    {
        $this->addContainsEventsEntityToCollection($args);
    }

    /**
     * @param LifecycleEventArgs<ObjectManager> $args
     */
    private function addContainsEventsEntityToCollection(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof ContainsEventsInterface) {
            $this->entities[] = $entity;
        }
    }

    public function preFlush(): void
    {
        foreach ($this->entityManager->getUnitOfWork()->getIdentityMap() as $class => $entities) {
            if (!\in_array(ContainsEventsInterface::class, class_implements($class), true)) {
                continue;
            }
            /** @var ContainsEventsInterface $entity */
            foreach ($entities as $entity) {
                $this->entities[] = $entity;
            }
        }
    }

    public function postFlush(): void
    {
        /**
         * @var array<array-key, EventInterface>
         */
        $events = [];
        foreach ($this->entities as $entity) {
            foreach ($entity->getRecordedEvents() as $domainEvent) {
                $events[] = $domainEvent;
            }
            $entity->clearRecordedEvents();
        }

        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event, $event::class);
        }
    }
}
