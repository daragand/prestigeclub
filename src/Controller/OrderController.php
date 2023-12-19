<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function index(OrderRepository $orderRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $order = $orderRepository->findBy(['users' => $this->getUser()]);

       
        return $this->render('order/commandes.html.twig', [
            'controller_name' => 'OrderController',
            'orders' => $order,
        ]);
    }

    #[Route('/order/confirmation/{id}', name: 'app_order_confirmation')]
    public function confirmation(Order $order): Response
    {
        return $this->render('payment/payment_success.html.twig', [
            'order' => $order,
        ]);
    }
    #[Route('/order/details/{id}', name: 'app_order_details')]
    public function details(Order $order): Response
    {
        return $this->render('order/details.html.twig', [
            'order' => $order,
        ]);
    }
}
