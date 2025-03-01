<?php

namespace App\Form;

use App\Entity\ProprieterTypeActiviter;
use App\Entity\TypeActiviter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProprieterTypeActiviterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomProprieter')
            ->add('typeActiviters', EntityType::class, [
                'class' => TypeActiviter::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProprieterTypeActiviter::class,
        ]);
    }
}
