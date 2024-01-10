<?php

namespace App\Controller\Admin;

use App\Entity\Photo;
use DateTimeImmutable;
use App\Form\PhotoType;
use App\Entity\Licencie;
use PharIo\Manifest\Email;
use Symfony\Component\Uid\Uuid;
use App\Form\PhotoCollectionType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Symfony\Component\Validator\Constraints\Date;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
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

    public function configureCrud(Crud $crud): Crud
    {
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
            TextField::new('firstname', 'Prénom'),
            TextField::new('lastname', 'Nom'),
            DateField::new('birthdate', 'Date de naissance'),
            EmailField::new('email', 'Email'),
            CollectionField::new('photos', 'Photos')
                ->setEntryType(PhotoCollectionType::class)
                ->onlyOnForms(),
            AssociationField::new('photos', 'Photos')
                ->hideOnForm()
                ->setTemplatePath('admin/licencie/photos.html.twig'),
                AssociationField::new('groupes', 'Groupes'),
                AssociationField::new('club', 'Clubs'),

        ];
    }



    //ajout du slug(UUID) au moment de la création
    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        //si l'entité n'est pas celle de la photo, alors on ne va pas plus loin.

        if (!($entityInstance instanceof Licencie)) {
            return;
        }

        //on créé un slug
        $slug = Uuid::v4()->__toString();

        //on ajoute la date de publication à la photo
        $entityInstance->setSlug($slug);
        //on ajoute la date de création du licencié sur  notion updatedAt
        $entityInstance->setUpdatedAt(new \DateTime());

        //la méthode ci-dessous permet de préserver les données et de les enregistrer dans la base.
        parent::persistEntity($em, $entityInstance);
    }
}
