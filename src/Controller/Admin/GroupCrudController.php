<?php

namespace App\Controller\Admin;

use App\Entity\Group;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class GroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Group::class;
    }
    public function configureActions(Actions $actions): Actions
    {

    return $actions
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
       //permet de masquer le bouton edit, new et delete dans la page detail pour le Club
        ->setPermissions([Action::NEW => 'ROLE_ADMIN', 
        Action::DELETE => 'ROLE_ADMIN',
         Action::EDIT => 'ROLE_ADMIN']);
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
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            
            AssociationField::new('clubs', 'Clubs')
            ->autocomplete()
            
            ,
            
        ];
    }
    
}
