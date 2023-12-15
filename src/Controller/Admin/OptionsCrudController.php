<?php

namespace App\Controller\Admin;

use App\Entity\Options;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OptionsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Options::class;
    }
public function configureCrud(Crud $crud): Crud
{
    return $crud
        ->setPageTitle('index', 'Options')
        ->setPageTitle('new', 'Ajouter une option')
        ->setPageTitle('edit', 'Modifier une option')
        ->setPageTitle('detail', 'Détail de l\'option')
        ->setSearchFields(['name', 'price'])
        ->setDefaultSort(['id' => 'DESC'])
        ->setEntityLabelInSingular('Option')
        ->setEntityLabelInPlural('Options');
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
