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
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use App\Repository\PhotoRepository;
use App\Repository\LivretRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{

    //contournement du Dashboard en placant un constructeur
    public function __construct(
        private OrderRepository $orderRepository, 
        private LivretRepository $livretRepository,
        private PhotoRepository $photoRepository,
        private UserRepository $userRepository,
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
            ->addJsFile('datatables.net-bs4/js/dataTables.bootstrap4.min.js')
            ->addJsFile('datatables.net-bs4/js/dataTables.bootstrap4.js')
            ->addJsFile('datatables.net-bs4/js/jquery.dataTables.min.js');
    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        
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
        
         
     
      
        
        return $this->render('Admin/DashboardAdmin.html.twig',[
            'commandes'=>$allOrders,
            'livrets'=>$allLivrets,
            'photos'=>$allPhotos,
            'downloadedPhotos'=>$downloadedPhotos,
            'parents'=>$parents,
            'nbPhotos'=>$nbPhotos
        ]);
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
        yield MenuItem::linkToCrud('Licenciés', 'fas fa-users', Licencie::class)
        ;
       
    }
    
}
