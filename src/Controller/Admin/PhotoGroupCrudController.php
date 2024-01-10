<?php

namespace App\Controller\Admin;

use App\Entity\PhotoGroup;
use Doctrine\ORM\EntityManagerInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Validator\Constraints\Date;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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
             /**
             * TextField ci-dessous sur photoGroupFile exploite Vich Uploader Bundle. Il génère un nom au fichier et le stocke dans un dosser uploads.
             */
            TextField::new('photoGroupFile', 'Photo de Groupe')
            ->setFormType(VichFileType::class)
            //ajout d'un template pour afficher les images dans la liste
            ->setTemplatePath('admin/photo/custom_imageGroup.html.twig'),
            DateField::new('datePublication', 'Date de publication')->hideOnForm(),
            AssociationField::new('groupID', 'Groupe'),
            AssociationField::new('club', 'Club'),
           
        ];
    }

    // création d'un écouteur pour intégrer la date de publication à la création de la photo de groupe

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        //si l'entité n'est pas celle de la photo de Groupe, alors on ne va pas plus loin.

        if (!($entityInstance instanceof PhotoGroup)) {
            return;

        }

        //on ajoute la date de publication
        $entityInstance->setDatePublication(new \DateTime());
        parent::persistEntity($em,$entityInstance);
    }
    
}
