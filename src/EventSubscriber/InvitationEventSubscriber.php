<?php

namespace App\EventSubscriber;

use App\Entity\Licencie;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;



use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class InvitationEventSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $container;
    private $parameterBag;

    public function __construct(MailerInterface $mailer,  ParameterBagInterface $parameterBag)
    {
        $this->mailer = $mailer;
       
        $this->parameterBag = $parameterBag;
    }

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
        //récupération de l'adresse mail de l'expéditeur dans le fichier .yaml (récupéré lui-même dans le fichier .env)
        $mailSender = $this->parameterBag->get('email_address');
        

        /**
         * suite à un problème de sérialisation des photos (à cause File), je bascule en contexte des données spéciques.
         */
        $mailer = $this->mailer;

        
        $uuid = $entity->getSlug();
        
        

        $email = (new TemplatedEmail())
            ->from($mailSender)
            ->to($emailLicencie)
            ->subject('Invitation à consulter vos photos')
            ->htmlTemplate('mail/invitation.html.twig')
            ->context([
                'uuid' => $uuid,
                'emailLicencie' => $emailLicencie,
            ]);

            $mailer->send($email);

    }
}

        