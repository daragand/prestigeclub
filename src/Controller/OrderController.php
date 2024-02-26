<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\Query\Parameter;
use App\Repository\OrderRepository;
use Liip\ImagineBundle\Model\Binary;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController

{
 
    
    #[Route('/order', name: 'app_order')]
    public function index(OrderRepository $orderRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $order = $orderRepository->findBy(['users' => $this->getUser()], ['paymentDate' => 'DESC']);

       
        return $this->render('order/commandes.html.twig', [
            'controller_name' => 'OrderController',
            'orders' => $order,
        ]);
    }

    #[Route('/order/confirmation/{uuidOrder}', name: 'app_order_confirmation')]
    public function confirmation(Order $order): Response
    {
        return $this->render('payment/payment_success.html.twig', [
            'order' => $order,
        ]);
    }
    #[Route('/order/details/{uuidOrder}', name: 'app_order_details')]
    public function details(Order $order): Response
    {
        return $this->render('order/details.html.twig', [
            'order' => $order,
        ]);
    }
   #[Route('order/telechargement/{uuidOrder}', name: 'app_order_download')]
   public function download(Order $order): BinaryFileResponse
   {
        //récupération de chemin du fichier zip dans la commande
         $zipFile = $order->getZipFile();
         $fileName = 'photos_'.$order->getId().'.zip';

         //si le fichier n'existe pas, alors on affiche un message d'erreur
         if(!file_exists(!$zipFile)){
            $this->addFlash('error','le fichier n\'existe pas.');
         }
         //la réponse ci-dessous permet de télécharger directement le fichier zip. Bien penser à mettre le lien vers cette fonction en _blank.
         $res = new BinaryFileResponse($zipFile);
         $res->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName);
         
         return $res;
   }
   
}
