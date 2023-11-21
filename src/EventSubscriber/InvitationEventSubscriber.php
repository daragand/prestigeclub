<?php

namespace App\EventSubscriber;

use App\Entity\Licencie;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class InvitationEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityPersistedEvent::class => ['sendInvitationEmail'],
        ];
    }

    public function sendInvitationEmail(AfterEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Licencie)) {
            return;
        }

        $emailLicencie = $entity->getEmail();

        $email = (new TemplatedEmail())
            ->from('');


    }
}


