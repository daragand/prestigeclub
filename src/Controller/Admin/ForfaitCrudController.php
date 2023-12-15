<?php

namespace App\Controller\Admin;

use App\Entity\Forfait;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ForfaitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Forfait::class;
    }
public function configureCrud(Crud $crud): Crud
{
    return $crud
        ->setPageTitle('index', 'Forfaits')
        ->setPageTitle('new', 'Ajouter un forfait')
        ->setPageTitle('edit', 'Modifier un forfait')
        ->setPageTitle('detail', 'Détail du forfait')
        ->setSearchFields(['name', 'price'])
        ->setDefaultSort(['id' => 'DESC'])
        ->setEntityLabelInSingular('Forfait')
        ->setEntityLabelInPlural('Forfaits');
}

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()
            ->hideOnDetail(),
            TextField::new('name','Forfait'),
            TextEditorField::new('description'),
        ];
    }
    
}
