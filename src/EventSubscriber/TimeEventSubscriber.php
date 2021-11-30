<?php


namespace App\EventSubscriber;

use App\Entity\Advert;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class TimeEventSubscriber implements EventSubscriberInterface
{


    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate
        ];
    }

    public function prePersist(LifecycleEventArgs $args ): void {
        $entity = $args->getEntity();
        if(!$entity instanceof Advert) {
            return;
        }
        $entity->setCreatedAt(new \DateTime('Europe/Paris'));
    }

    public function preUpdate(LifecycleEventArgs $args ): void {

        $entity = $args->getEntity();
        if(!$entity instanceof Advert) {
            return;
        }

        // Update only if is publishedAt parameter
        //workflow.[advert_publishing].entered.[publish]
        $changes = $args->getEntityManager()->getUnitOfWork()->getEntityChangeSet($args->getObject());
        if($changes != null && (sizeof($changes) > 0) && (isset($changes['state'])) ){
            if(isset($changes['state'][1]) && $changes['state'][1] == "published"){
                $entity->setPublishedAt(new \DateTime('Europe/Paris'));
            }
        }

    }

}