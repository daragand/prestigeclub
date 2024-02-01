<?php

namespace App\Controller;


use App\Entity\Cart;
use App\Entity\Forfait;
use App\Entity\OptionList;
use App\Repository\CartRepository;
use App\Repository\ForfaitRepository;
use App\Repository\LicencieRepository;
use App\Repository\OptionListRepository;
use App\Repository\OptionsRepository;
use App\Repository\PhotoRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PanierController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }
    

    #[Route('/panier', name: 'app_panier')]
    public function addToCart(
        Request $request,
        ForfaitRepository $forfaitRepository,
        OptionsRepository $optionsRepository,
        PhotoRepository $photoRepository,
        CartRepository $cartRepository,
        UserRepository $userRepository,
        LicencieRepository $licencieRepository,
        EntityManagerInterface $entityManager
    ): RedirectResponse {

        $prods = $request->request->all();

        //on récupère le panier de l'utilisateur (s'il existe
    $cart = $cartRepository->findOneBy(['users' => $this->getUser()]);
    
    if (!$cart) {
        $cart = new Cart();
        $cart->setUsers($this->getUser())
        ->setUuidCart(uniqid());
        $entityManager->persist($cart);
    }

    /**
     * Dans cette partie, on va parcourir le tableau $prods pour récupérer les informations et les ajouter au panier.
     * 
     */
    
     //on récupère le forfait s'il a été sélectionné
    if (isset($prods['forfait'])) {
        $forfait = $forfaitRepository->findOneBy(['name' => $prods['forfait']]);
    }
    //création d'un montant à 0 et qui sera incrémenté des montants des options et du forfait
    $amount = 0;

    foreach ($prods as $key => $value) {

        /**
         * Récupération des options. 
         * On explose la clé pour récupérer l'id de l'option et l'id de la photo.
         * On crée un objet OptionList et on lui ajoute les options via l'identifiant et les photos.
         * Par défaut la quantité est à 1.
         * Ajout de l'objet OptionList au panier.
         */
        if (strpos($key, 'option') !== false) {
            $itemOption = explode("-", $key);
            $optionList = new OptionList();
            $optionList->setOptions($optionsRepository->findOneBy(['id' => intval($itemOption[1])]));
            $optionList->setPhotos($photoRepository->findOneBy(['id' => intval($itemOption[2])]));
            $optionList->setQuantity(1);
            $cart->addOptionList($optionList);
            $amount=($amount + $optionList->getOptions()->getPrice());
        } elseif (strpos($key, 'championPh') !== false) {
            /**
             * Dans cette partie, on récupère les photos sélectionnées par l'utilisateur dans le cadre du forfait champion puis on les intègre au panier.
             */
            foreach ($value as $photoId) {
                $cart->addPhoto($photoRepository->findOneBy(['id' => intval($photoId)]));
            }
            
        } elseif ($key == 'forfait') {
            
            $cart->setForfait($forfait);
            $amount=($amount + $forfait->getPrice());
          
          
        } elseif (strpos($key, 'licencie') !== false) {
            //ajout du licencié au panier.
            $cart->setLicencie($licencieRepository->findOneBy(['slug' => $value]));

            // si le forfait est prestige, on ajoute les 4 premières photos. 
          // TODO : voir pour placer la gestion des photos selon les forfaits de manière plus dynamique et refactoriser le code. Il manque de clarté.
        
          if ($forfait->getName() === 'Prestige') {
            
            $photos = $licencieRepository->findOneBy(['slug' => $value])->getPhotos()->slice(0, 4);
            foreach ($photos as $photo) {
                $cart->addPhoto($photo);
            }
        }
        }
    }
    
    //pour la gestion du montant total du panier
    $cart->setAmount($amount);
    $entityManager->persist($cart);

    $entityManager->flush();

    return $this->redirectToRoute('app_panier_visualiser');


         
    }   

    #[Route('/panier/delete/{id}', name: 'app_panier_delete')]
    public function deleteFromCart(
        Cart $cart
    ): RedirectResponse {

        
        if (!$cart) {
            return $this->redirectToRoute('app_panier_visualiser');
        }

        if($cart->getOptionLists()){
            foreach ($cart->getOptionLists() as $optionList) {
                $optionList->setCart(null);
                
            }
        }elseif($cart->getForfait()){
            $cart->setForfait(null);
        }elseif($cart->getPhotos()){
            foreach ($cart->getPhotos() as $photo) {
                $photo->removeCart($cart);
            }
        }elseif($cart->getUsers()){
            $cart->setUsers(null);
        }
       
        
            //Maintenant que le panier est vidé de son contenu relationnel, on peut le supprimer
        $this->entityManager->remove($cart);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_panier_visualiser');
    }
    #[Route('/panier/visualiser',name:'app_panier_visualiser')]
    public function visualiser(CartRepository $cartRepository): Response
    {
        
        
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'panier'=>$cartRepository->findOneBy(['users'=>$this->getUser()])
        ]);
    }
    #[Route('/panier/deleteitem/{item}', name: 'app_panier_deleteitem')]
    public function deleteItemFromCart(

    )
    {
        //TODO : à faire
        return $this->redirectToRoute('app_panier_visualiser');
    }
    


    #[Route('/panier/checkout/{id}',name:'app_panier_checkout')]
    public function checkout(CartRepository $cartRepository): Response
    {
        
        
        return $this->render('panier/checkout.html.twig', [
            'controller_name' => 'PanierController',
            'panier'=>$cartRepository->findOneBy(['users'=>$this->getUser()])
        ]);
    }
}