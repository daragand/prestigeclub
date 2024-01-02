<?php

namespace App\Controller\Admin;

use App\Entity\Club;
use App\Entity\Order;
use App\Entity\Photo;
use App\Entity\Livret;
use App\Entity\Address;
use App\Entity\Forfait;
use App\Entity\Options;
use App\Entity\Licencie;
use App\Entity\OptionList;
use App\Entity\PhotoGroup;
use App\Repository\ClubRepository;
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use App\Repository\PhotoRepository;
use App\Repository\LivretRepository;
use App\Repository\LicencieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardController extends AbstractDashboardController
{

    //contournement du Dashboard en placant un constructeur
    public function __construct(
        private OrderRepository $orderRepository, 
        private LivretRepository $livretRepository,
        private PhotoRepository $photoRepository,
        private UserRepository $userRepository,
        private ClubRepository $clubRepository,
        private LicencieRepository $licencieRepository,
        private ChartBuilderInterface $chartBuilder
        )
    {
        
    }
    //fonction pour assets css et JS
    public function configureAssets(): Assets
    {
        return Assets::new()
        ->addCssFile('css/admin.css')
            ->addCssFile('datatables.net-bs4/css/responsive.dataTables.min.css')
            ->addCssFile('datatables.net-bs4/css/dataTables.bootstrap4.min.css')
            ->addCssFile('datatables.net-bs4/css/dataTables.bootstrap4.css')
           
            ->addJsFile('datatables.net/js/jquery.min.js')
       
            ->addJsFile('datatables.net/js/jquery.dataTables.min.js')
            ->addJsFile('datatables.net/js/datatable-basic.init.js');
        
    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        //si l'utilisateur est un admin, on affiche le dashboard admin
        if ($this->isGranted('ROLE_ADMIN')) {
           //liste des commandes toutes confondues trié par date de paiement
         $allOrders = $this->orderRepository->findBy([],['paymentDate'=>'DESC']);            //liste des livrets
         $allLivrets = $this->livretRepository->findAll();
     //liste et nombres de photos
         $allPhotos = $this->photoRepository->findAll();
         $downloadedPhotos = $this->photoRepository->findBy(['downloaded'=>true]);
         $nbPhotos = count($allPhotos);

    
      // liste des utilisateurs Parents ayant passés commandes. GetSingleScalarResult() permet de retourner un seul résultat : le nombre de parents distincts ayant passé commande
      $parents = $this->orderRepository->createQueryBuilder('o')
      ->select('COUNT(DISTINCT u.id) as userCount')
      ->join('o.users', 'u')
      ->getQuery()
      ->getSingleScalarResult();

      //graphiques des ventes
      $chart = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
      $chart->setData([
          'labels' => ['Photos', 'Commandes'],
          'datasets' => [
              [
                  'label' => 'Ventes',
                  'backgroundColor' => ['#FF6384', '#36A2EB'],
                  'borderColor' => ['#FF6384', '#36A2EB'],
                  'data' => [$nbPhotos, count($allOrders)],
              ],
          ],
      ]);
   
     return $this->render('Admin/DashboardAdmin.html.twig',[
         'commandes'=>$allOrders,
         'livrets'=>$allLivrets,
         'photos'=>$allPhotos,
         'downloadedPhotos'=>$downloadedPhotos,
         'parents'=>$parents,
         'nbPhotos'=>$nbPhotos,
            'chart'=>$chart,
     ]);
        }

        //si l'utilisateur est un club, on affiche le dashboard club
        if ($this->isGranted('ROLE_CLUB')) {
            $user = $this->userRepository->findOneBy(['email'=>$this->getUser()->getUserIdentifier()]);
            
            $club = $this->clubRepository->findOneBy(['id'=>intval($user->getClub()[0]->getId())]);
            
            /**
             * Récupération des commandes du club
             */
            $orders = $this->orderRepository->createQueryBuilder('o')
            ->join('o.licencie', 'lic')
            ->join('lic.club', 'club')
            ->where('club = :clubId')
            ->setParameter('clubId', $club->getId())
            ->getQuery()
            ->getResult()
            ;

            $amountTotal = 0;
            foreach ($orders as $order) {
                $amountTotal += $order->getAmount();
            }
            
            $photos = $this->photoRepository->createQueryBuilder('p')
            ->join('p.licencie', 'licencie')
            ->where('licencie.club = :club')
            ->setParameter('club', $club->getId())
            ->getQuery()
            ->getResult();
            
            $licencies= $club->getLicencie();
            

            
            
            $nbPhotos = count($photos);
            $downloadedPhotos = $this->photoRepository->findBy(['downloaded'=>true]);
            $nbDownloadedPhotos = count($downloadedPhotos);
            
            
            
            
            return $this->render('admin/DashboardClub.html.twig',[
                'commandes'=>$orders,
                'photos'=>$photos,
                'nbPhotos'=>$nbPhotos,
                'downloadedPhotos'=>$downloadedPhotos,
                'nbDownloadedPhotos'=>$nbDownloadedPhotos,
                'pourcentage'=>$amountTotal*0.1,
                
                
            ]);
        }
        
    }
   
public function configureCrud(): Crud
{
    return parent::configureCrud()
   
    ;

}
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle("<img src=\"images/logoj.png\" alt=\" Logo de Prestige Club\"/>");
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Commandes')->setCssClass('border-bottom border-2');
        yield MenuItem::linkToCrud('Toutes les commandes', 'fa-solid fa-table-list', Order::class)->setController(OrderCrudController::class);
        yield MenuItem::linkToCrud('Commandes en cours', 'fa-solid fa-list', Order::class)->setController(OrderToControllCrudController::class);
        yield MenuItem::linkToCrud('Commandes traitées', 'fa-solid fa-list-check', Order::class)->setController(OrderTreatedCrudController::class);
        yield MenuItem::linkToCrud('Commandes terminées', 'fa-regular fa-square-check', Order::class)->setController(OrderFinishedCrudController::class);

        
        // ->setCssClass('nav nav-pills nav-sidebar flex-column')
       

        yield MenuItem::section('Photos')->setCssClass('border-bottom border-2');
        yield MenuItem::linkToCrud('Photos individuelles', 'fas fa-images', Photo::class);
        yield MenuItem::linkToCrud('Photos de groupes', 'fa-solid fa-image-portrait', PhotoGroup::class);
        yield MenuItem::linkToCrud('Livrets', 'fa-solid fa-file', Livret::class);
        yield MenuItem::linkToCrud('Les Options', 'fas fa-cog', OptionList::class);
       
        

        yield MenuItem::section('Gestion')->setCssClass('border-bottom border-2');
        yield MenuItem::linkToCrud('Adresses', 'fas fa-map-marker-alt', Address::class);
        yield MenuItem::linkToCrud('Forfaits', 'fas fa-money-bill-wave', Forfait::class);
        yield MenuItem::linkToCrud('Options', 'fas fa-cog', Options::class);
        yield MenuItem::linkToCrud('Clubs','fa-solid fa-landmark', Club::class);
        yield MenuItem::linkToCrud('Licenciés', 'fas fa-users', Licencie::class);
        //section vide pour créer un espace entre les deux menus
        yield MenuItem::section(' ');
        yield MenuItem::linkToLogout('Déconnexion', 'fa-solid fa-sign-out-alt');
       
    }
    
}
