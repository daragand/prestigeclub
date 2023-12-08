<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Forfait;
use App\Entity\OptionList;
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

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(
        Request $request,
        ForfaitRepository $forfaitRepository,
        OptionsRepository $optionsRepository,
        PhotoRepository $photoRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $prods=$request->request->all();
        //récupération des clés du tableau
        $keys=array_keys($prods);

        //récupération du forfait dans une variable s'il existe
       if(array_key_exists('forfait',$prods)){
           $forfaitId=$prods['forfait'];
       }
        
        
        //création du panier

        $panier= new Cart();
        $panier->setUsers($this->getUser());
         //ajout du forfait au panier s'il existe et qu'il n'est pas null ainsi que le montant
        if(isset($forfaitId) && $forfaitId!=null){
            $forfait=$forfaitRepository->findBy(['id' => intval($forfaitId)]);
           
            if($forfait){
                /**
                 * Bien que l'id soit unique, la méthode findBy renvoie un tableau. il s'agit donc de récupérer le premier élément du tableau
                 */
                $panier->setForfait($forfait[0])
                
                ->setAmount($panier->getAmount()+$forfait[0]->getPrice());
            }
            
        }


        
       foreach($keys as $key){
        if($key!='forfait'){
            /**
             * Explosion de la clé pour récupérer l'id de l'option et l'id de la photo.
             * La clé est de la forme option-1-1. le premier élément est l'option, le second l'id de l'option et le troisième l'id de la photo.
             */
            $itemOption = explode("-",$key);
            


            //création de l'optionList avec l'option concernée, les photos et une quantité à 1
            $optLst = new OptionList();
            $optLst->setOptions($optionsRepository->findOneBy(['id'=>intval($itemOption[1])]))
            ->setPhotos($photoRepository->findOneBy(['id'=>intval($itemOption[2])]))
            ->setQuantity(1);
            $entityManager->persist($optLst);


            //ajout dans le panier de l'option et modification du montant du panier
            $panier->addOptionList($optLst)
            ->setAmount($panier->getAmount()+$optLst->getOptions()->getPrice());

            
            
        }
       }
      

       $entityManager->flush();

        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'panier'=>$panier
        ]);
    }
}
