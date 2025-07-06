<?php
namespace App\Form;

use App\Entity\ActiviterExercice;
use App\Entity\TypeActiviter; // Ou TypeExercice si tu corriges le modèle
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActiviterExerciceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Si c'est bien TypeActiviter que tu veux lier ici...
            ->add('typeActiviter', EntityType::class, [
                'class' => TypeActiviter::class, // Ou TypeExercice::class ?
                'choice_label' => 'nomType', // Adapte selon le nom de la propriété dans TypeActiviter
                'placeholder' => 'Choisir un type',
            ])
            ->add('dataActiviters', CollectionType::class, [
                'entry_type' => DataActiviterType::class,
                'entry_options' => ['label' => false], // Pas de label pour chaque sous-formulaire
                'allow_add' => true,      // Permet d'ajouter de nouvelles dataActiviters via JS
                'allow_delete' => true,   // Permet de supprimer des dataActiviters via JS
                'by_reference' => false, // Important pour que les adders/removers soient appelés sur l'entité ActiviterExercice
                // Attributs pour le JS (voir template)
                'attr' => [
                    'class' => 'dataActiviters-collection',
                ],
            ])
            ;
            // activiter_id sera géré par la relation Doctrine
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ActiviterExercice::class,
        ]);
    }
}