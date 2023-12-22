<?php

namespace App\Controller;

use App\Service\ZipDownload;
use App\Service\MailingService;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    #[Route('/page', name: 'app_page')]
    public function index(): Response
    {
        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
    #[Route('/zip', name: 'app_test_zip')]
    public function zip(OrderRepository $orderRepository,ZipDownload $zip,MailingService $mailingService,EntityManagerInterface $entityManager): Response
    {
        $order = $orderRepository->findOneBy(['id' => 74]);

        $downloadZip = $zip->zipCreate($order);
        $order->setZipFile($downloadZip);
        $entityManager->persist($order);
        $entityManager->flush();
        $mailingService->downloadPhoto($order,$downloadZip);

        

        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
}
