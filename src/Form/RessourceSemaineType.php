<?php

namespace App\Form;

use App\Entity\RessourceSemaine;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RessourceSemaineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('semaine', TextType::class, [
                'label' => 'Semaine',
                'disabled' => true, // Désactive le champ
            ])
            ->add('cm', IntegerType::class, [
                'label' => 'CM',
            ])
            ->add('td', IntegerType::class, [
                'label' => 'TD',
            ])
            ->add('tp', IntegerType::class, [
                'label' => 'TP',
            ])
            ->add('ds', IntegerType::class, [
                'label' => 'DS',
            ])
            ->add('sae', IntegerType::class, [
                'label' => 'SAE',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RessourceSemaine::class,  // Associe bien le formulaire à un objet RessourceSemaine
        ]);
    }
}
