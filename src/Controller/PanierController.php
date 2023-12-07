<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Forfait;
use App\Repository\ForfaitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(
        Request $request,
        ForfaitRepository $forfaitRepository
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
dd($panier,$keys);

       foreach($keys as $key){

       }

       

        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }
}
