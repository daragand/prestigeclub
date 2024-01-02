<?php

namespace App\Controller\Admin;

use App\Entity\OptionList;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OptionListCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OptionList::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            AssociationField::new('photos', 'Photos')
                ->hideOnForm()
                ->setTemplatePath('admin/licencie/photos.html.twig'),
            AssociationField::new('options', 'Options'),
        ];
    }
    
}
