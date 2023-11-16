<?php

namespace App\Controller\Admin;

use App\Entity\Photo;
use DateTimeImmutable;
use App\Form\PhotoType;
use App\Entity\Licencie;
use App\Form\PhotoCollectionType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Symfony\Component\Validator\Constraints\Date;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LicencieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Licencie::class;
    }

    public function configureCrud(Crud $crud): Crud{
        return $crud
            ->setPageTitle('index', 'Licenciés')
            ->setPageTitle('new', 'Ajouter un licencié')
            ->setPageTitle('edit', 'Modifier un licencié')
            ->setPageTitle('detail', 'Détail du licencié')
            ->setSearchFields(['name', 'firstname', 'birthday', 'adress', 'zip', 'city', 'phone', 'email', 'licence', 'certif', 'cotisation', 'forfait', 'group'])
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityLabelInSingular('Licencié')
            ->setEntityLabelInPlural('Licenciés');
    }

    
    public function configureFields(string $pageName): iterable
    {

        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('firstname', 'Prénom'),
            TextField::new('lastname', 'Nom'),
            DateField::new('birthdate', 'Date de naissance'),
            CollectionField::new('photos', 'Photos')
            ->setEntryType(PhotoCollectionType::class)
            ->onlyOnForms(),
            AssociationField::new('photos', 'Photos')
            ->hideOnForm()
            ->setTemplatePath('admin/licencie/photos.html.twig'),
            
        ];
        
    }


}
