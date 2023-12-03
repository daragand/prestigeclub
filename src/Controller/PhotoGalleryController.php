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
    #[Route('/photos', name: 'app_photos_index')]
    public function index(LicencieRepository $licencieRepository, UserRepository $userRepository, PhotoRepository $photoRepository): Response
    {

        /**
         * Si l'usager n'est pas pas connecté, je le redirige vers la page de connexion par sécurité.
         */
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        /**
         * Je récupère l'adresse email de l'utilisateur connecté, pour récupérer son profil Utilisateur
         */
        $userConnected = $this->getUser();
        $email = $userConnected->getUserIdentifier();

        /**
         * Je récupère le profil Utilisateur de l'utilisateur connecté
         */
        $user = $userRepository->findOneBy(['email' => $email]);


        $userLicencies = $user->getLicencies();
       
        
        /**
         * Si l'utilisateur n'a qu'un seul licencié, je le redirige directement vers la galerie de photos de ce licencié
         */
        if (!$userLicencies && count($userLicencies)<=1){
           $slug = $userLicencies[0]->getSlug();
              return $this->redirectToRoute('app_photo_gallery', ['slug' => $slug]);
        }
        
        /**
         * Si l'utilisateur a plusieurs licenciés, je lui affiche la page de présentation des licenciés, en récupérant les photos de chaque licencié
         */
        $infosLicencies = [];
        
        foreach ($userLicencies as $userLicencie) {
            $licencieInfo = [
                'licencie' => $userLicencie,
                'photos' => $photoRepository->findBy(['licencie' => $userLicencie]),
                'club' => $userLicencie->getClub(),
                'groupe' => $userLicencie->getGroupes(),

            ];
            array_push($infosLicencies, $licencieInfo);
        }
       
        
       

        return $this->render('photo_gallery/accueil.html.twig', [
            'controller_name' => 'PhotoGalleryController',
            'licencies' => $infosLicencies,
        ]);
    }
    
    
    
    
    #[Route('/photos/gallery', name: 'app_photo_gallery')]
    public function presentation(
        PhotoRepository $photoRepository,
        UserRepository $userRepository,
        LicencieRepository $licencieRepository
        ): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $userConnected = $this->getUser();
      
        
        $email = $userConnected->getUserIdentifier();

        
        $user = $userRepository->findOneBy(['email' => $email]);
        $userLicencies = $user->getLicencies();
        
        //ajouter une fonction IN ARRAY
        foreach ($userLicencies as $userLicencie) {
            
            $photos[] = $photoRepository->findBy(['licencie' => $userLicencie]);
        }
           
        /**
         * Si l'utilisateur est un usager, on affiche les photos liées à ses licenciés
         */
        // if ($roles[0] === 'ROLE_USER') {
        //     // $licencies = $user->getLicencies();
            
          
        //     $photos = $photoRepository->findBy(['users' => $this->getUser()]);
        // } else {
        //     return $this->redirectToRoute('admin');
        // }
        // dd($photos);
        
        $photos = array_merge(...$photos);

        return $this->render('photo_gallery/gallery.html.twig', [
            'controller_name' => 'PhotoGalleryController',
            'photos' => $photos,
            'licencie' => $userLicencie,
        ]);
    }

    
}
