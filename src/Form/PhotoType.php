<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\Photo;
use App\Entity\Licencie;
use Symfony\Component\Form\AbstractType;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('photos', DropzoneType::class, [
                'mapped' => false, // On ne lie pas ce champ à la propriété 'image' de l'entité Article
                'multiple'=> true,
                'label' => 'Téléversez les images du licencié',
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('groupes', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'name',
                'label' => 'Groupe',
                'attr' => [
                    'class' => 'form-control mt-3 mb-3'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Licencie::class,
        ]);
    }
}
