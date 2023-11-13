<?php

namespace App\Controller\Admin;

use App\Entity\Photo;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PhotoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Photo::class;
    }
public function configureCrud(Crud $crud): Crud
{
    return $crud
        ->setPageTitle('index', 'Photos')
        ->setPageTitle('new', 'Ajouter une photo individuelle')
        ->setPageTitle('edit', 'Modifier une photo individuelle')
        ->setPageTitle('detail', 'Détail de la photo individuelle')
        ->setSearchFields(['name'])
        ->setDefaultSort(['id' => 'DESC'])
        ->setEntityLabelInSingular('Photo')
        ->setEntityLabelInPlural('Photos');
}
    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
