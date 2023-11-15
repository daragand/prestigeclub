<?php

namespace App\Controller\Admin;

use App\Form\PhotoType;
use App\Entity\Photo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Symfony\Component\Validator\Constraints\Date;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
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
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnDetail()
            ->hideOnForm(),
            yield Field::new('photoFile', 'Photo individuelle')
        ->setFormType(PhotoType::class) // use the custom form type
        ->onlyOnForms(),
            

            BooleanField::new('downloaded', 'téléchargée')->hideOnForm(),
            AssociationField::new('licencie', 'licencié')
        ];
    }
    
    /**
     * Création d'un écouteur d'événement pour ajouter la date de publication avec le persistEntity
     */ 

     public function persistEntity(EntityManagerInterface $em, $entityInstance): void
     {
        //si l'entité n'est pas celle de la photo, alors on ne va pas plus loin.

        if (!($entityInstance instanceof Photo)) {
            return;

        }
        //on ajoute la date de publication si c'est bien l'instance

         $entityInstance->setDatePublication(new Date());
         //la méthode ci-dessous permet de préserver les données et de les enregistrer dans la base.
         parent::persistEntity($em, $entityInstance);
     } 
}
