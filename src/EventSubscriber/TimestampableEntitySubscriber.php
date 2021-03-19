<?php

namespace App\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class TimestampableEntitySubscriber implements EventSubscriber
{
    private const CREATED_AT_FIELD = "createdAt";
    private const UPDATED_AT_FIELD = "updatedAt";

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::postPersist,
            Events::preUpdate,
            Events::postUpdate
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (property_exists($entity, self::CREATED_AT_FIELD)) {
            $entity->setCreatedAt(new \DateTimeImmutable());
        }

        $this->updateTimestampable($args);
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->updateTimestampable($args);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->updateTimestampable($args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->updateTimestampable($args);
    }

    private function updateTimestampable(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (property_exists($entity, self::UPDATED_AT_FIELD)) {
            $entity->setUpdatedAt(new \DateTime());
        }
    }
}
