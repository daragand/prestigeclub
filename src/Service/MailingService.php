<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Order;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailingService
{
    private ParameterBagInterface $parameterBag;
    private MailerInterface $mailer;

    public function __construct(ParameterBagInterface $parameterBag,MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->parameterBag = $parameterBag;

    }
   
    public function downloadPhoto(Order $order,string $zipFile)
    {
        if($order->getZipFile()){

            $mailSender = $this->parameterBag->get('email_address');
            $domain = $this->parameterBag->get('domain');
            
            $email = (new TemplatedEmail())
            ->from($mailSender)
            ->to($order->getUsers()->getEmail())
            ->subject('Commande N°'.$order->getId().': lien de téléchargement de vos photos')
            ->htmlTemplate('mail/telechargement.html.twig')
            ->context([
                'zipLink' => $zipFile,
                'emailContact' => $mailSender,
                'lastName' => $order->getLicencie()->getLastName(),
                'firstName' => $order->getLicencie()->getFirstName(),
                'uuidOrder' => $order->getUuidOrder(),
                'domain' => $domain
                
            ]);
            $this->mailer->send($email);
        }
      

    }
    public function sendDeleteConfirmation(User $user)
    {
        $mailSender = $this->parameterBag->get('email_address');
        $domain = $this->parameterBag->get('domain');
        
        $email = (new TemplatedEmail())
        ->from($mailSender)
        ->to($user->getEmail())
        ->subject('Suppression de votre compte')
        ->htmlTemplate('mail/deleteuser.html.twig')
        ->context([
            'emailContact' => $mailSender,
            'lastName' => $user->getLastName(),
            'firstName' => $user->getFirstName(),
            'uuidUser' => $user->getUuidUser(),
            'domain' => $domain
            
        ]);
        $this->mailer->send($email);
    
    }



}