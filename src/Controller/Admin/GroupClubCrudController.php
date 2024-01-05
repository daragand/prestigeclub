<?php

namespace App\Controller\Admin;


use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;


class GroupClubCrudController extends GroupCrudController
{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, 'Groupes associé à votre club');
    }
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        /**
         * $this-getUser()->getClub() ne permet de pas de récupérer le club directement.
         * On récupère l'eamil de l'utilisateur connecté, puis on récupère le club de l'utilisateur
         */

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        
        $club= $user->getClub();
        
        $clubId = $club[0]->getId();
       
        
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
          ->join('entity.clubs', 'club')  
        ->andWhere('club.id = :clubId')
            ->setParameter('clubId', $clubId)
            ->orderBy('entity.id', 'ASC')
            ;
            //ordre croissant pour traiter les anciennes commandes en premier
    }
}