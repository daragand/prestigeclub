<?php

namespace App\Controller;

use App\Entity\Licencie;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
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


    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/tolog/invitation/{slug}', name: 'app_login_invitation')]
    public function loginInvitation(Licencie $licencie,UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {

    if ($this->getUser() && $this->isGranted('ROLE_PARENT')) {

    //Si l'utilisateur est déjà connecté,recherche de l'utilisateur connecté et ajout de la relation avec le licencié
    $email = $this->getUser()->getUserIdentifier();
    
    $user = $userRepository->findOneBy(['email' => $email]);
    $user->addLicency($licencie);
    $entityManager->persist($user);
    $entityManager->flush();
    $this->addFlash('success', 'le licencié a été bien ajouté à votre compte.');
            return $this->redirectToRoute('app_photos_index');

            //si l'utilisateur est déjà connecté et qu'il correspond à un club ou est admin, on le redirige vers la page adéquate.
        }elseif ($this->getUser() && !$this->isGranted('ROLE_PARENT')){
            $this->addFlash('error','Vous n\'avez pas les droits pour ajouter un licencié');
            return $this->redirectToRoute('admin');
        }
        

 

    }
}
