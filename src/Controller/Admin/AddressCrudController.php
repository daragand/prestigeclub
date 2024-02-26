<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AddressCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Address::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Adresses')
            ->setPageTitle('new', 'Ajouter une adresse')
            ->setPageTitle('edit', 'Modifier une adresse')
            ->setPageTitle('detail', 'Détail de l\'adresse')
            ->setSearchFields(['city', 'address', 'zip'])
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityLabelInSingular('Adresse')
            ->setEntityLabelInPlural('Adresses');
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('address'),
            TextField::new('zip'),
            TextField::new('city'),
        ];
    }
    
}
