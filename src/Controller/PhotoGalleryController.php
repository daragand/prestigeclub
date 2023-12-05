<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Forfait;
use App\Entity\Options;
use App\Entity\Licencie;
use App\Repository\UserRepository;
use App\Repository\PhotoRepository;
use App\Repository\ForfaitRepository;
use App\Repository\OptionsRepository;
use App\Repository\LicencieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    
    
    
    
    #[Route('/photos/gallery/{slug}', name: 'app_photo_gallery')]
    public function presentation(
        PhotoRepository $photoRepository,
        UserRepository $userRepository,
        Licencie $licencie,
        ForfaitRepository $forfaitRepository,
        OptionsRepository $optionsRepository
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
           
      
        
        $photos = array_merge(...$photos);

        /**
         * Récupération des forfaits et des options
         */
        $forfaits = $forfaitRepository->findAll();
        $options = $optionsRepository->findAll();


        return $this->render('photo_gallery/gallery.html.twig', [
            'controller_name' => 'PhotoGalleryController',
            'photos' => $photos,
            'licencie' => $userLicencie,
            'sportif' => $licencie,
            'forfaits' => $forfaits,
            'options' => $options,
        ]);
    }

    
}
