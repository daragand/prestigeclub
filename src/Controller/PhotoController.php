<?php

namespace App\Controller;

use App\Form\PhotoType;
use App\Entity\Licencie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhotoController extends AbstractController
{
    #[Route('/photo', name: 'app_photo')]
    public function index(): Response
    {
        return $this->render('photo/visionphoto.html.twig', [
            'controller_name' => 'PhotoController',
        ]);
    }
    #[Route('/ajout-photo', name: 'app_photo_ajout')]
    public function addPhoto(
        Request $request, EntityManagerInterface $entityManager
    ): Response
    {
        $licencie = new Licencie();

        $formAddPhoto = $this->createForm(PhotoType::class, $licencie);
        //si le formulaire est valide
        $formAddPhoto->handleRequest($request);

        if ($formAddPhoto->isSubmitted() && $formAddPhoto->isValid()) {


        //ajout du licencié et de ses photos dans la base de données
        $entityManager->persist($licencie);
        $entityManager->flush();
        }

        return $this->render('photo/ajoutphoto.html.twig', [
            'controller_name' => 'PhotoController',
            'formAddPhoto' => $formAddPhoto->createView()
        ]);
    }
}
