<?php

namespace App\Form;

use App\Entity\ProprieterTypeActiviter;
use App\Entity\TypeActiviter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeActiviterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomType')
            ->add('proprieter', EntityType::class, [
                'class' => ProprieterTypeActiviter::class,
                'choice_label' => 'nomProprieter',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TypeActiviter::class,
        ]);
    }
}
