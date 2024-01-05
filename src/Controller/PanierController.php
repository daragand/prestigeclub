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
    
    if (isset($prods['forfait'])) {
        $forfait = $forfaitRepository->findOneBy(['name' => $prods['forfait']]);
    }
    $amount = 0;

    foreach ($prods as $key => $value) {
        if (strpos($key, 'option') !== false) {
            $itemOption = explode("-", $key);
            $optionList = new OptionList();
            $optionList->setOptions($optionsRepository->findOneBy(['id' => intval($itemOption[1])]));
            $optionList->setPhotos($photoRepository->findOneBy(['id' => intval($itemOption[2])]));
            $optionList->setQuantity(1);
            $cart->addOptionList($optionList);
            $amount=($amount + $optionList->getOptions()->getPrice());
        } elseif (strpos($key, 'championPh') !== false) {
            foreach ($value as $photoId) {
                $cart->addPhoto($photoRepository->findOneBy(['id' => intval($photoId)]));
            }
            
        } elseif ($key == 'forfait') {
            
            $cart->setForfait($forfait);
            $amount=($amount + $forfait->getPrice());
          
        } elseif (strpos($key, 'licencie') !== false) {

            $cart->setLicencie($licencieRepository->findOneBy(['slug' => $value]));
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