<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Service\ZipDownload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function zip(OrderRepository $orderRepository,ZipDownload $zip): Response
    {
        $order = $orderRepository->findOneBy(['id' => 74]);

        $downloadZip = $zip->zipCreate($order);
        dd($downloadZip);

        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
}
