<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhotoGalleryController extends AbstractController
{
    #[Route('/photo/gallery', name: 'app_photo_gallery')]
    public function index(): Response
    {
        return $this->render('photo_gallery/gallery.html.twig', [
            'controller_name' => 'PhotoGalleryController',
        ]);
    }
}
