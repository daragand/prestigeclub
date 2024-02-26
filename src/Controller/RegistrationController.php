<?php

namespace App\Controller;

use App\Entity\Licencie;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/invitation/{slug}', name: 'app_invitation')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        //Si l'usager est déjà connecté et qu'il dispose d'un role parent, nous ajoutons le licencié directement
        if ($this->getUser() && $this->isGranted('ROLE_PARENT')) {

            //récupération du licencié
            $licencieParUuid = $entityManager->getRepository(Licencie::class)->findOneBy(['slug' => $request->attributes->get('slug')]);

            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
            //ajout de la relation du licencié au nouvel utilisateur
            $user->addLicency($licencieParUuid);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Vous êtes déjà connecté et le licencié a été bien ajouté à votre compte.');
            return $this->redirectToRoute('app_photos_index');
        }
        //récupération du licencié par son uuid(slug)
        $licencieParUuid = $entityManager->getRepository(Licencie::class)->findOneBy(['slug' => $request->attributes->get('slug')]);
        $user = new User();
        //attribution de l'adresse mail du licencié au nouvel utilisateur. Il est libre de la modifier.
        $user->setEmail($licencieParUuid->getEmail());
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
                
            )
            //attribution d'un Uniqid
            ->setUuidUser(uniqid())
            //attribution du rôle ROLE_USER
            ->setRoles(['ROLE_PARENT'])
            //ajout de la relation du licencié au nouvel utilisateur
            ->addLicency($licencieParUuid);


            $entityManager->persist($user);
            $entityManager->flush();
            

            return $this->redirectToRoute('app_photos_index');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'licencie' => $licencieParUuid
        ]);
    }

    
}
