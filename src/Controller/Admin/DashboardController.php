<?php

namespace App\Controller\Admin;

use App\Entity\Club;
use App\Entity\Photo;
use App\Entity\Livret;
use App\Entity\Address;
use App\Entity\Forfait;
use App\Entity\Licencie;
use App\Entity\Order;
use App\Entity\PhotoGroup;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('Admin/DashboardAdmin.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Prestige Club');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Commandes')->setCssClass('border-bottom border-2');
        yield MenuItem::linkToCrud('Commandes', 'fas fa-map-marker-alt', Order::class);
        
        // ->setCssClass('nav nav-pills nav-sidebar flex-column')
       

        yield MenuItem::section('Photos')->setCssClass('border-bottom border-2');
        yield MenuItem::linkToCrud('Photos individuelles', 'fas fa-images', Photo::class);
        yield MenuItem::linkToCrud('Photos de groupes', 'fa-solid fa-image-portrait', PhotoGroup::class);
        yield MenuItem::linkToCrud('Livrets', 'fa-solid fa-file', Livret::class);
       
        

        yield MenuItem::section('Gestion')->setCssClass('border-bottom border-2');
        yield MenuItem::linkToCrud('Adresses', 'fas fa-map-marker-alt', Address::class);
        yield MenuItem::linkToCrud('Forfaits', 'fas fa-money-bill-wave', Forfait::class);
        yield MenuItem::linkToCrud('Clubs','fa-solid fa-landmark', Club::class);
        yield MenuItem::linkToCrud('Licenciés', 'fas fa-users', Licencie::class)
        ;
       
    }
    
}
