<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Forfait;
use App\Entity\OptionList;
use App\Repository\CartRepository;
use App\Repository\ForfaitRepository;
use App\Repository\OptionListRepository;
use App\Repository\OptionsRepository;
use App\Repository\PhotoRepository;
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
   
    

    #[Route('/panier', name: 'app_panier')]
    public function addToCart(
        Request $request,
        ForfaitRepository $forfaitRepository,
        OptionsRepository $optionsRepository,
        PhotoRepository $photoRepository,
        CartRepository $cartRepository,
        EntityManagerInterface $entityManager
    ): void {

        $prods = $request->request->all();

    $cart = $cartRepository->findOneBy(['users' => $this->getUser()]);
    

    $forfait = null;
    if (isset($prods['forfait'])) {
        $forfait = $forfaitRepository->findOneBy(['name' => $prods['forfait']]);
    }

    foreach ($prods as $key => $value) {
        if (strpos($key, 'option') !== false) {
            $itemOption = explode("-", $key);
            $optionList = new OptionList();
            $optionList->setOptions($optionsRepository->findOneBy(['id' => intval($itemOption[1])]));
            $optionList->setPhotos($photoRepository->findOneBy(['id' => intval($itemOption[2])]));
            $optionList->setQuantity(1);
            $cart->addOptionList($optionList);
        } elseif (strpos($key, 'championPh') !== false) {
            $cart->addPhoto($photoRepository->findOneBy(['id' => intval($value)]));
        } elseif ($key == 'forfait') {
            $cart->setForfait($forfait);
        }
    }
    

    $entityManager->flush();

    // return $this->redirectToRoute('app_panier_visualiser');


         
    }   

    #[Route('/panier/delete/{id}', name: 'app_panier_delete')]
    public function deleteFromCart(
        Cart $cart,
        CartRepository $cartRepository,
        EntityManagerInterface $entityManager
    ): RedirectResponse {
        

        $entityManager->remove($cart);
        $entityManager->flush();

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
}