<?php

namespace App\Controller\Admin;

use App\Entity\Club;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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
    public function configureActions(Actions $actions): Actions
    {

    return $actions
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
       //permet de masquer le bouton edit, new et delete dans la page detail pour le Club
        ->setPermissions([Action::NEW => 'ROLE_ADMIN', 
        Action::DELETE => 'ROLE_ADMIN',
         Action::EDIT => 'ROLE_ADMIN']);
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom du Club'),
            TextField::new('logoFile', 'Logo du club')
            ->setFormType(VichFileType::class)
            //ajout d'un template pour afficher les images dans la liste
            ->setTemplatePath('admin/photo/custom_logoclub.html.twig'),
            AssociationField::new('sport', 'Sport'),
            AssociationField::new('address', 'Adresse'),
        ];
    }
    
}
