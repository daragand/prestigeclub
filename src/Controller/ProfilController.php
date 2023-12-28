<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            'user' => $user,
        ]);
    }
    #[Route('/profil/edit', name: 'app_profil_edit')]
    public function edit(Request $request,EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $formUser = $this->createForm(UserType::class, $user);
        $formUser->handleRequest($request);

        if ($formUser->isSubmitted() && $formUser->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_profil');

        }
        
        return $this->render('profil/edit.html.twig', [
            'controller_name' => 'ProfilController',
            'user' => $user,
            'formUser' => $formUser->createView(),
        ]);
    }
    #[Route('/profil/delete/confirmation/{uuidUser}', name: 'app_profil_delete')]
    public function delete(EntityManagerInterface $entityManager, $uuidUser): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $user = $entityManager->getRepository(User::class)->findOneBy(['uuidUser' => $uuidUser]);
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_logout');
    }
}
