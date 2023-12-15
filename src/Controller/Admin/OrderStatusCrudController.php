<?php

namespace App\Controller\Admin;

use App\Entity\OrderStatus;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderStatusCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderStatus::class;
    }
public function configureCrud(Crud $crud): Crud
{
    return $crud
        ->setPageTitle('index', 'Statuts de commande')
        ->setPageTitle('new', 'Ajouter un statut de commande')
        ->setPageTitle('edit', 'Modifier un statut de commande')
        ->setPageTitle('detail', 'Détail du statut de commande')
        ->setSearchFields(['name'])
        ->setDefaultSort(['id' => 'DESC'])
        ->setEntityLabelInSingular('Statut de commande')
        ->setEntityLabelInPlural('Statuts de commande');
}
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    
}
