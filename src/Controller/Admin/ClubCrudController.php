<?php

namespace App\Controller\Admin;

use App\Entity\Club;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ClubCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Club::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Clubs')
            ->setPageTitle('new', 'Ajouter un club')
            ->setPageTitle('edit', 'Modifier un club')
            ->setPageTitle('detail', 'Détail du club')
            ->setSearchFields(['name', 'adress', 'zip'])
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityLabelInSingular('Club')
            ->setEntityLabelInPlural('Clubs');
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom du Club'),
            AssociationField::new('address', 'Adresse'),
        ];
    }
    
}
