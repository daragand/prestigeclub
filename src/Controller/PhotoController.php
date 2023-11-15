<?php

namespace App\Controller;

use App\Form\PhotoType;
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
    public function addPhoto(): Response
    {

        $formAddPhoto = $this->createForm(PhotoType::class);

        return $this->render('photo/ajoutphoto.html.twig', [
            'controller_name' => 'PhotoController',
            'formAddPhoto' => $formAddPhoto->createView()
        ]);
    }
}
