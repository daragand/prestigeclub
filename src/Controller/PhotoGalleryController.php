<?php

namespace App\Controller;

use App\Entity\Licencie;
use App\Entity\User;
use App\Repository\LicencieRepository;
use App\Repository\UserRepository;
use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhotoGalleryController extends AbstractController
{
    #[Route('/photo/gallery', name: 'app_photo_gallery')]
    public function presentation(
        PhotoRepository $photoRepository,
        UserRepository $userRepository,
        LicencieRepository $licencieRepository
        ): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();
        $roles = $user->getRoles();
        $licencie = $licencieRepository->findBy(['users' => $this->getUser()]);
    //    $licencies = $licencieRepository->findBy(['users' => $this->getUser()]);
            dd($licencies);
        /**
         * Si l'utilisateur est un usager, on affiche les photos liées à ses licenciés
         */
        if ($roles[0] === 'ROLE_USER') {
            // $licencies = $user->getLicencies();
            
          
            $photos = $photoRepository->findBy(['users' => $this->getUser()]);
        } else {
            return $this->redirectToRoute('admin');
        }
        dd($photos);
        
        

        return $this->render('photo_gallery/gallery.html.twig', [
            'controller_name' => 'PhotoGalleryController',
            'photos' => $photos
        ]);
    }

    
}
