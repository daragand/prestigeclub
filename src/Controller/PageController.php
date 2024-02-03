<?php

namespace App\Controller;

use App\Service\ZipDownload;
use App\Service\MailingService;
use App\Repository\OrderRepository;
use App\Repository\PhotoGroupRepository;
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
    #[Route('/page/mentions-legales', name: 'app_page_mentions_legales')]
    public function mentionsLegales(): Response
    {
        return $this->render('page/mentions_legales.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
    #[Route('/page/cgv', name: 'app_page_cgv')]
    public function cgv(): Response
    {
        return $this->render('page/cgv.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
    #[Route('/page/politique-confidentialite', name: 'app_page_confidentialite')]
    public function confidentialite(): Response
    {
        return $this->render('page/confidentialite.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
    #[Route('/zip', name: 'app_test_zip')]
    public function zip(OrderRepository $orderRepository,ZipDownload $zip,MailingService $mailingService,EntityManagerInterface $entityManager, PhotoGroupRepository $photoGroupRepository): Response
    {
        $order = $orderRepository->findOneBy(['id' => 48 ]);
        
        $photoGroupe = $photoGroupRepository->createQueryBuilder('phg')
        ->join('phg.club', 'club')
        ->join('phg.groupID','groupe')
        ->where('club.id = :clubID')
        ->andWhere('groupe.id = :groupID')
        ->setParameter('clubID', $order->getLicencie()->getClub()->getId())
        ->setParameter('groupID',$order->getLicencie()->getGroupes()->getId() )
        ->getQuery()
        ->getResult();

dd($photoGroupe);

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
