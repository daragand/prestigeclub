<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }
public function configureCrud(Crud $crud): Crud
{
    return $crud
        ->setPageTitle('index', 'Commandes')
        ->setPageTitle('new', 'Ajouter une commande')
        ->setPageTitle('edit', 'Modifier une commande')
        ->setPageTitle('detail', 'Détail de la commande')
        ->setDefaultSort(['id' => 'DESC'])
        ->setEntityLabelInSingular('Commande')
        ->setEntityLabelInPlural('Commandes');
}
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()
            ->hideOnDetail(),
            AssociationField::new('users', 'Utilisateur'),
            AssociationField::new('cart', 'Formule'),
            AssociationField::new('orderStatus', 'Statut de la commande'),
        ];
    }
    
}
