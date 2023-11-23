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
            //attribution du rôle ROLE_USER
            ->setRoles(['ROLE_USER'])
            //ajout de la relation du licencié au nouvel utilisateur
            ->addLicency($licencieParUuid);


            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_photo_gallery');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
