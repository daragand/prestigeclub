<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

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
            DateField::new('paymentDate', 'Date de la commande'),
            AssociationField::new('forfait', 'Forfait'),
            CollectionField::new('optionLists', 'Options')->hideOnForm(),
            AssociationField::new('orderStatus', 'Statut de la commande'),
            NumberField::new('amount', 'Montant en €')->hideOnForm(),
        ];
    }
    
}
