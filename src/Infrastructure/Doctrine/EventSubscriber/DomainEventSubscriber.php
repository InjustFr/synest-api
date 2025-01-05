<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\EventSubscriber;

use App\Core\Domain\Shared\ContainsEventsInterface;
use App\Core\Domain\Shared\EventInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsDoctrineListener(event: Events::prePersist, priority: 500, connection: 'default')]
#[AsDoctrineListener(event: Events::preUpdate, priority: 500, connection: 'default')]
#[AsDoctrineListener(event: Events::preRemove, priority: 500, connection: 'default')]
#[AsDoctrineListener(event: Events::preFlush, priority: 500, connection: 'default')]
#[AsDoctrineListener(event: Events::postFlush, priority: 500, connection: 'default')]
final class DomainEventSubscriber
{
    /**
     * @var ArrayCollection<array-key, ContainsEventsInterface>
     */
    private Collection $entities;

    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private EntityManagerInterface $entityManager,
    ) {
        $this->entities = new ArrayCollection();
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

    private function addContainsEventsEntityToCollection(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof ContainsEventsInterface) {
            $this->entities->add($entity);
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
                $this->entities->add($entity);
            }
        }
    }

    public function postFlush(): void
    {
        /**
         * @var ArrayCollection<array-key, EventInterface>
         */
        $events = new ArrayCollection();
        foreach ($this->entities as $entity) {
            foreach ($entity->getRecordedEvents() as $domainEvent) {
                $events->add($domainEvent);
            }
            $entity->clearRecordedEvents();
        }
        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event, $event::class);
        }
    }
}
