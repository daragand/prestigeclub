<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SecurityController extends AbstractController
{

 /**
     * Pour récupérer les données d'adresse mail d'expéditeur et du mailer, déclaration d'un objet ParameterBagInterface
     * 
     */
    private $parameterBag;
    private $mailer;

    public function __construct(MailerInterface $mailer,  ParameterBagInterface $parameterBag)
    {
        $this->mailer = $mailer;
       
        $this->parameterBag = $parameterBag;
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/connexion', name: 'app_login_parent')]
    public function requestLoginLink(UserRepository $userRepository,LoginLinkHandlerInterface $loginLinkHandler,Request $request,MailerInterface $mailer): Response
    {

        /**
         * Si une requête POST est envoyée, on récupère l'adresse mail et on envoie le lien de connexion
         */
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

            dd($email);
            $user = $userRepository->findOneBy(['email' => $email]);
            //récupération de l'adresse mail de l'expéditeur dans le fichier .yaml (récupéré lui-même dans le fichier .env)
            $mailSender = $this->parameterBag->get('email_address');
            
            // création du lien de connexion pour l'utilisateur
            $loginLinkDetails = $loginLinkHandler->createLoginLink($user);

            $email = (new TemplatedEmail())
                ->from($mailSender)
                ->to($user->getEmail())
                ->subject('Votre lien de connexion à Prestige Club')
                ->priority(TemplatedEmail::PRIORITY_HIGH)
                ->html('<p>Voici votre lien de connexion : <a href="' . $loginLinkDetails->getUrl() . '">Connexion</a></p>');

            $mailer->send($email);

            // Page de confirmation d'envoi du lien de connexion
            return $this->render('security/login_link.html.twig');
        }

        return $this->render('security/login_parent.html.twig');
    }


    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
