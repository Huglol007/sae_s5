<?php

namespace App\Form;

use App\Entity\Enseignant;
use App\Entity\TypeEnseignant;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;

class EnseignantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('utilisateur', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getFirstName() . ' ' . $user->getLastName();
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.last_name', 'ASC');
                },
                'label' => 'Sélectionner un utilisateur'
            ])
            ->add('typeEnseignant', EntityType::class, [
                'class' => TypeEnseignant::class,
                'choice_label' => 'type',
                'label' => 'Type d’enseignant'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter l’enseignant'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Enseignant::class,
        ]);
    }
}

