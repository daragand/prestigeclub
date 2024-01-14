<?php

namespace App\Controller\Admin;
use App\Entity\Order;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;


class OrderFinishedCrudController extends OrderCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, 'Commandes en cours de traitement');
    }
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
          ->join('entity.orderStatus', 'orderStatus')  
        ->andWhere('orderStatus.name = :status')
            ->setParameter('status', 'Finalisée')
            ->orderBy('entity.id', 'ASC')
            ;
            //ordre croissant pour traiter les anciennes commandes en premier
    }
}