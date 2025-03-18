<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SemaineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('ressource_semaines', CollectionType::class, [
                'entry_type' => RessourceSemaineType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null, // Pas d'entité spécifique, c'est juste un formulaire de collection
        ]);
    }
}
