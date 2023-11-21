<?php

namespace App\EventSubscriber;

use App\Entity\Licencie;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;

class InvitationEventSubscriber implements EventSubscriberInterface
{
    public function __construct(string $emailSender)
    {
        $this->emailSender = $senderEmail;
    }
    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityPersistedEvent::class => ['sendInvitationEmail'],
        ];
    }

    public function sendInvitationEmail(AfterEntityPersistedEvent $event, MailerInterface $mailer)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Licencie)) {
            return;
        }

        $emailLicencie = $entity->getEmail();
        $mailSender=$this->getParameters('email_sender');

        $email = (new Email())
            ->from($container->getParameter('email_sender'));


    }
}


