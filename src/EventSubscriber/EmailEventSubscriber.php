<?php


namespace App\EventSubscriber;


use App\Entity\Advert;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class EmailEventSubscriber implements EventSubscriberInterface
{

    private MailerInterface $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::preUpdate
        ];
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function preUpdate(LifecycleEventArgs $args ): void {

        $entity = $args->getEntity();
        if(!$entity instanceof Advert) {
            return;
        }

        // Update only if is publishedAt parameter
        $changes = $args->getEntityManager()->getUnitOfWork()->getEntityChangeSet($args->getObject());
        if($changes != null && (sizeof($changes) > 0) && (isset($changes['state'])) ){
            if(isset($changes['state'][1]) && $changes['state'][1] == "published"){
                $email = (new TemplatedEmail())
                    ->to(new Address($entity->getEmail()))
                    ->subject('Publication acceptée')
                    ->htmlTemplate('email/advert_published.html.twig')
                    ->context([
                        'publication_id' => $entity->getId(),
                        'publication_title' => $entity->getTitle(),
                        'publication_content' => $entity->getContent(),
                        'publication_date' => $entity->getPublishedAt(),
                    ]);
                $this->mailer->send($email);
            }
        }
    }
}