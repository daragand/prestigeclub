<?php

namespace App\Controller\Admin;

use App\Entity\Livret;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LivretCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Livret::class;
    }
public function configureCrud(Crud $crud): Crud
{
    return $crud
        ->setPageTitle('index', 'Livrets')
        ->setPageTitle('new', 'Ajouter un livret')
        ->setPageTitle('edit', 'Modifier un livret')
        ->setPageTitle('detail', 'Détail du livret')
        ->setSearchFields(['name'])
        ->setDefaultSort(['id' => 'DESC'])
        ->setEntityLabelInSingular('Livret')
        ->setEntityLabelInPlural('Livrets');
}
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
    
        ];
    }
    
}
