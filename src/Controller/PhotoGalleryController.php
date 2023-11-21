<?php

namespace App\Controller;

use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhotoGalleryController extends AbstractController
{
    #[Route('/photo/gallery', name: 'app_photo_gallery')]
    public function presentation(PhotoRepository $photoRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $roles = $this->getUser()->getRoles();

        if (in_array('ROLE_USER', $roles)) {
            

            $user = $this->getUser();
        $licencies = $user->getLicencies();

        $photos = $photoRepository->findBy();
        }
        
        

        return $this->render('photo_gallery/gallery.html.twig', [
            'controller_name' => 'PhotoGalleryController',
        ]);
    }
}
