<?php

namespace App\Controller;

use App\Service\ZipDownload;
use Stripe\Stripe;
use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\Forfait;
use PharIo\Manifest\Url;
use App\Entity\OrderStatus;
use Stripe\Checkout\Session;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PaymentController extends AbstractController
{
    private $parameterBag;
    private EntityManagerInterface $entityManager;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator)
    {
        $this->parameterBag = $parameterBag;
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
    }
    #[Route('/order/create-session-stripe/{id}', name: 'app_payment')]
    public function stripeCheckout($id): RedirectResponse
    {
        $cart = $this->entityManager->getRepository(Cart::class)->findOneBy(['id' => $id]);

        if (!$cart) {
            return $this->redirectToRoute('app_panier_visualiser');
        }

        //pour contourner le Lazy Loading, on récupère le forfait en tant que référence
        $forfaitName= $cart->getForfait()->getName();

        
       /**
        * Lors de la récupération du panier, il semble que le forfait ne veut pas apparaitre. Utilisation donc du get_Reference
        */

        $forfait = $this->entityManager->getReference(Forfait::class, $cart->getForfait()->getId());
        
        

       

        $productStripe = [];

        if ($cart->getOptionLists()) {
            foreach ($cart->getOptionLists() as $optionList) {
                $productStripe[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $optionList->getOptions()->getPrice() * 100,
                        'product_data' => [
                            'name' => $optionList->getOptions()->getName() . ' - photo : ' . $optionList->getPhotos()->getId(),
                        ],
                    ],
                    'quantity' => 1,
                ];
            }
        }
        
        if($cart->getForfait()){
           
            $productStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $forfait->getPrice() * 100,
                    'product_data' => [
                        'name' => 'forfait '.$forfaitName,
                    ],
                ],
                'quantity' => 1,
            ];
        }

        if(count($productStripe) === 0){
            $this->addFlash('error', 'Votre panier est vide et ne peut donc pas faire l\objet d\'un paiement.');
            return $this->redirectToRoute('app_panier_visualiser');
        }

        
        $stripeSecretKey = $this->parameterBag->get('secret_key_stripe');

        Stripe::setApiKey($stripeSecretKey);
        header('Content-Type: application/json');

       



        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getUserIdentifier(),
            'payment_method_types' => ['card'],
            'line_items' => [[
                $productStripe
            ]],
            'mode' => 'payment',
            'success_url' => $this->urlGenerator->generate('app_payment_success', ['id' => $cart->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->urlGenerator->generate('app_payment_error', ['id' => $cart->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return new RedirectResponse($checkout_session->url);
    }

    #[Route('/order/success/{id}', name: 'app_payment_success')]
    public function success(Cart $cart, UserRepository $userRepository,ZipDownload $zip): RedirectResponse
    {

        
        //création d'une commande depuis le panier
        $order = new Order();
        $order->setAmount($cart->getAmount())
            ->setPaymentDate(new \DateTime())
            ->setUsers($this->getUser())
            ->setLicencie($cart->getLicencie())
            ->setUuidOrder(uniqid());

        if($cart->getOptionLists()) {
            foreach ($cart->getOptionLists() as $optionList) {
                $order->addOptionList($optionList);
            }
        }
        if($cart->getForfait()) {
            
            $order->setForfait($cart->getForfait());
        }
        /**
         * Pour la gestion du statut de la commande. S'il s'agit d'une commande de forfait Prestige, le statut est automatiquement mis à "en attente de validation"
         */
         //si c'est un forfait prestige ou qu'une option a été ajoutée
         if($cart->getForfait()->getName() == 'Prestige'||$cart->getOptionLists()){
            $orderStatus = $this->entityManager->getRepository(OrderStatus::class)->findOneBy(['name' => 'En cours de traitement']);
            $order->setOrderStatus($orderStatus);
            }elseif($cart->getForfait()->getName() == 'Champion'){
                $orderStatus = $this->entityManager->getRepository(OrderStatus::class)->findOneBy(['name' => 'Payée']);
                $order->setOrderStatus($orderStatus);
            }
        
       
        //suppression du panier (placement à null les relations) et enregistrement de la commande
        $this->entityManager->persist($order);
        
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
        $this->entityManager->remove($cart);
        
        $this->entityManager->flush();

        $zip->zipCreate($order);
        
       return $this->redirectToRoute('app_order_confirmation', ['id' => $order->getId()]);
     
    }
    #[Route('/order/error/{id}', name: 'app_payment_error')]
    public function error(): RedirectResponse
    {

        $this->addFlash('error', 'Le règlement de votre commande a échoué. N\'hésitez pas à réessayer.');
        return $this->redirectToRoute('app_panier_visualiser');
    }

    
}
