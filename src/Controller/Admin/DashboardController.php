<?php

namespace App\Controller\Admin;

use App\Entity\Club;
use App\Entity\Group;
use App\Entity\Order;
use App\Entity\Photo;
use App\Entity\Sport;
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
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\LivretRepository;
use App\Repository\LicencieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
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
        private ClubRepository $clubRepository,
        private LicencieRepository $licencieRepository,
        private ChartBuilderInterface $chartBuilder,
        private EntityManagerInterface $em
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
//liste des clubs
         $clubs = $this->clubRepository->findAll();
         //liste des licenciés
         $licencies = $this->licencieRepository->findAll();
    
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
            'clubs'=>$clubs,
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
            
            
            
            

            
            
            $nbPhotos = count($photos);
            
            dd($club->getGroups());
           
            
            
            
            return $this->render('admin/DashboardClub.html.twig',[
                'commandes'=>$orders,
                'photos'=>$photos,
                'nbPhotos'=>$nbPhotos,
                'club'=>$club,
                'licencies'=>$club->getLicencie(),
                'groupes'=>$club->getGroups(),
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
        if ($this->isGranted('ROLE_CLUB')) {
            yield MenuItem::section('Tableau de bord', 'fa-solid fa-tachometer-alt')->setCssClass('border-bottom border-2');
            
            yield MenuItem::linkToCrud('les Commandes', 'fa-solid fa-table-list', Order::class)->setController(OrderCrudController::class);
            yield MenuItem::linkToCrud('Licenciés du Club', 'fas fa-users', Licencie::class)->setController(LicencieClubCrudController::class);
            yield MenuItem::linkToCrud('Groupes', 'fas fa-people-group', Group::class)->setController(GroupClubCrudController::class);
        }else{
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
       
        yield MenuItem::section('Clubs')->setCssClass('border-bottom border-2');
        yield MenuItem::linkToCrud('Clubs','fa-solid fa-landmark', Club::class);
        yield MenuItem::linkToCrud('Licenciés', 'fas fa-users', Licencie::class);
        yield MenuItem::linkToCrud('Groupes', 'fas fa-people-group', Group::class);
        yield MenuItem::linkToCrud('Sport','fas fa-futbol', Sport::class);

        yield MenuItem::section('Gestion')->setCssClass('border-bottom border-2');
        yield MenuItem::linkToCrud('Adresses', 'fas fa-map-marker-alt', Address::class);
        yield MenuItem::linkToCrud('Forfaits', 'fas fa-money-bill-wave', Forfait::class);
        yield MenuItem::linkToCrud('Options', 'fas fa-cog', Options::class);
        
        }
        //section vide pour créer un espace entre les deux menus
        
        yield MenuItem::section(' ');
        yield MenuItem::linkToLogout('Déconnexion', 'fa-solid fa-sign-out-alt');
       
    }
    #[Route('/admin/purge', name: 'admin_purge')]
    public function purge(EntityManagerInterface $em): Response
    {
        /**
         * Cette fonction consiste à supprimer les fichiers (photos et archives) et les licenciés pour toutes les commandes de plus de 6 mois.
         */


         //vérification de l'utilisateur

        if (!$this->isGranted('ROLE_ADMIN')){
            $this->addFlash(
               'error',
               'Vous n\'avez pas les droits pour accéder à cette fonctionnalité'
            );
            return $this->redirectToRoute('admin');
        }else{
            //récupération des commandes de plus de 6 mois
            $oldLicencies = $this->licencieRepository->createQueryBuilder('lic')
            ->where('lic.updatedAt < :date')
            ->setParameter('date', new \DateTime('-6 months'))
            ->getQuery()
            ->getResult();
            
            
            //suppression des fichiers et des licenciés
            foreach ($oldLicencies as $licencie) {
               
                $orders = $licencie->getOrders();
                    if($orders){
                        foreach ($orders as $order) {
                            $zipFile = $order->getZipFile();
                            if(file_exists($zipFile)){
                                unlink($zipFile);
                            }
                            if($order->getOptionLists()){
                                foreach ($order->getOptionLists() as $optionList) {
                                    
                                    $em->remove($optionList);
                                    
                                }
                                
                            }
                            
                            $em->remove($order);
                            
                        }
                    }
                $photos = $licencie->getPhotos();
                foreach ($photos as $photo) {
                    
                     $em->remove($photo);
                    $em->flush();
                }
               
                $livrets = $licencie->getLivrets();
                        if($livrets){
                            foreach ($livrets as $livret) {
                                
                                $em->remove($livret);
                                
                               
                            }
                        }
                        $em->remove($licencie);
                        
                        $em->flush();
                }
                
            }
            $this->addFlash(
                'success',
                'Les commandes de plus de 6 mois ont été supprimées'
             );
                return $this->redirectToRoute('admin');
        }

        #[Route('/admin/club/{id}', name: 'admin_club_info')]
    public function info($id): Response
    {
        

        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }
        
        
        $club = $this->em->getRepository(Club::class)->findOneBy(['id' => $id]);
        $licencies = $club->getLicencie();
        
        return $this->render('admin/clubinfodashboard.html.twig',[
            'club' => $club,
            'licencies' => $licencies,
        
        ]);
    }
        
    }


