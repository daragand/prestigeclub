<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\OrderStatus;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PaymentController extends AbstractController
{
    private $parameterBag;
    private EntityManagerInterface $entityManager;

    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager)
    {
        $this->parameterBag = $parameterBag;
        $this->entityManager = $entityManager;
    }
   #[Route('/order/create-session-stripe/{id}', name: 'app_payment')]
    public function stripeCheckout(Cart $cart):RedirectResponse
    {
        
        if(!$cart){
            return $this->redirectToRoute('app_panier_visualiser');
        }
        
        
        $productStripe = [];

        if($cart->getOptionLists()){
            foreach($cart->getOptionLists() as $optionList){
                $productStripe[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $optionList->getOptions()->getPrice()*100,
                        'product_data' => [
                            'name' => $optionList->getOptions()->getName(). ' - photo : ' . $optionList->getPhotos()->getId(),
                        ],
                    ],
                    'quantity' => 1,
                ];
            }
        }elseif($cart->getForfait()){
            $productStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $cart->getForfait()->getPrice()*100,
                    'product_data' => [
                        'name' => $cart->getForfait()->getName(),
                    ],
                ],
                'quantity' => 1,
            ];
        }

        dd($productStripe);
        $stripeSecretKey = $this->parameterBag->get('secret_key_stripe');
        
        Stripe::setApiKey($stripeSecretKey);
        header('Content-Type: application/json');
        
        $domain = $this->parameterBag->get('domain');
        
       

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getUserIdentifier(),
            'payment_method_types' => ['card'],
          'line_items' => [[
            $productStripe
          ]],
          'mode' => 'payment',
          'success_url' => $domain . '/success.html',
          'cancel_url' => $domain . '/cancel.html',
        ]);

        return new RedirectResponse($checkout_session->url);

    }

    #[Route('/order/success/{id}', name: 'app_payment_success')]
    public function success(Cart $cart): RedirectResponse
    {
        //création d'une commande depuis le panier
        $order = new Order();
        $order->setAmount($cart->getAmount())
            ->setPaymentDate(new \DateTime())
            ->setOrderStatus($this->entityManager->getRepository(OrderStatus::class)->findOneBy(['name' => 'payé']))
            ->setUsers($this->getUser());

if($cart->getOptionLists()){
    foreach($cart->getOptionLists() as $optionList){
        $order->addOptionList($optionList);
    }
}elseif($cart->getForfait()){
    $order->setForfait($cart->getForfait());
}
            
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_panier_visualiser');
    }

}