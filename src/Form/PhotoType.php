<?php

namespace App\Form;

use App\Entity\Licencie;
use App\Entity\Photo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('photos', FileType::class, [
                'mapped' => false, // On ne lie pas ce champ à la propriété 'image' de l'entité Article
                'label' => 'Téléversez les images de l\'article',
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('licencie', null, [
                'label' => 'Licencié',
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
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
