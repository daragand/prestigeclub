<?php

namespace App\Controller\Admin;

use App\Entity\Group;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class GroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Group::class;
    }
public function configureCrud(Crud $crud): Crud{
    return $crud
        ->setPageTitle('index', 'Groupes')
        ->setPageTitle('new', 'Ajouter un groupe')
        ->setPageTitle('edit', 'Modifier un groupe')
        ->setPageTitle('detail', 'Détail du groupe')
        ->setSearchFields(['name'])
        ->setDefaultSort(['id' => 'DESC'])
        ->setEntityLabelInSingular('Groupe')
        ->setEntityLabelInPlural('Groupes');
}
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name'),
            AssociationField::new('clubs'),
            
        ];
    }
    
}
