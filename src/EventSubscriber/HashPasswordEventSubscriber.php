<?php


namespace App\EventSubscriber;

use App\Entity\AdminUser;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HashPasswordEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher){}

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate
        ];
    }

    public function prePersist(LifecycleEventArgs $args ): void {
        $entity = $args->getEntity();
        if(!$entity instanceof AdminUser) {
            return;
        }
        $this->hashPassword($entity);
    }

    public function preUpdate(LifecycleEventArgs $args ): void {
        $entity = $args->getEntity();
        if(!$entity instanceof AdminUser) {
            return;
        }
        $this->hashPassword($entity);
    }

    private function hashPassword(AdminUser $userEntity) : void {
        if(!empty($userEntity->getPlainPassword())){
            $hashedPassword = $this->passwordHasher->hashPassword(
                $userEntity,
                $userEntity->getPlainPassword()
            );
            $userEntity->setPassword($hashedPassword);
        }
    }

}