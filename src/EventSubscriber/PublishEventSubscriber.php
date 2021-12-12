<?php


namespace App\EventSubscriber;

use App\Entity\Advert;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class PublishEventSubscriber implements EventSubscriberInterface
{

    private MailerInterface $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function onPublish(Event $event): void {

        $advert = $event->getSubject();
        if(!$advert instanceof Advert) {
            return;
        }

        $advert->setPublishedAt(new \DateTime());

        $email = (new TemplatedEmail())
                ->to(new Address($advert->getEmail()))
                ->subject('Publication acceptée')
                ->htmlTemplate('email/advert_published.html.twig')
                ->context([
                    'advert' => $advert,
            ]);

        $this->mailer->send($email);

    }

    public static function getSubscribedEvents(): array
    {
        return [
            'workflow.advert_publishing.enter.published' => 'onPublish',
        ];
    }

}