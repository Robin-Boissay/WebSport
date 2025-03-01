<?php

namespace App\Form;

use App\Entity\Activiter;
use App\Entity\DataActiviter;
use App\Entity\ProprieterTypeActiviter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DataActiviterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('valeur')
            ->add('ProprieterActiviter', EntityType::class, [
                'class' => ProprieterTypeActiviter::class,
                'choice_label' => 'nomProprieter',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DataActiviter::class,
        ]);
    }
}
