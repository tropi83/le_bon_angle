<?php


namespace App\EventSubscriber;

use App\Entity\Advert;
use App\Repository\AdminUserRepository;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Message;

class CreateAdvertEventSubscriber  implements EventSubscriberInterface
{
    private MailerInterface $mailer;
    private AdminUserRepository $adminUserRepository;
    public function __construct(MailerInterface $mailer, AdminUserRepository $adminUserRepository)
    {
        $this->mailer = $mailer;
        $this->adminUserRepository = $adminUserRepository;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::postPersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $args ): void {
        $advert = $args->getEntity();
        if(!$advert instanceof Advert) {
            return;
        }
        $advert->setCreatedAt(new \DateTime());
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function postPersist(LifecycleEventArgs $args ): void {
        $advert = $args->getEntity();
        if(!$advert instanceof Advert) {
            return;
        }

        $admins = $this->adminUserRepository->findAll();
        $emailAdresses = [];
        foreach ($admins as $admin){
            array_push($emailAdresses, new Address($admin->getEmail()));
        }

        $email = (new TemplatedEmail())
            ->to(...$emailAdresses)
            ->subject('Nouvelle publication')
            ->htmlTemplate('email/advert_created.html.twig')
            ->context([
                'advert' => $advert
            ]);
        $this->mailer->send($email);
    }

}