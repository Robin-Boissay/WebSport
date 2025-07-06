<?php

namespace App\Form;

use App\Entity\Activiter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActiviterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomActiviter', TextType::class, [
                'label' => 'Nom de la séance',
                'required' => true,
            ])
            ->add('startedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure de début',
                'data' => new \DateTimeImmutable(), // Pré-remplir avec maintenant
            ])
            // La collection principale pour les exercices
            ->add('activiterExercices', CollectionType::class, [ // Assure-toi que la propriété dans Activiter s'appelle 'exercices'
                'entry_type' => ActiviterExerciceType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false, // Important !
                'label' => false,
                 // Attributs pour le JS
                'attr' => [
                    'class' => 'exercices-collection',
                ],
            ])
            // user_id sera géré dans le contrôleur
            // created_at sera géré automatiquement si tu utilises un Timestampable behavior ou manuellement
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activiter::class,
        ]);
    }
}