<?php

namespace App\Controller\Admin;

use App\Entity\PhotoGroup;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PhotoGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PhotoGroup::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Photos de groupe')
            ->setPageTitle('new', 'Ajouter une photo de groupe')
            ->setPageTitle('edit', 'Modifier une photo de groupe')
            ->setPageTitle('detail', 'Détail de la photo de groupe')
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityLabelInSingular('Photo de Groupe')
            ->setEntityLabelInPlural('Photos de Groupe');
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            ImageField::new('path'),
           
        ];
    }
    
}
