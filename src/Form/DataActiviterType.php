<?php
namespace App\Form;

use App\Entity\DataActiviter;
use App\Entity\ValeurProprieter;
use App\Entity\ProprieterActiviter;
use App\Entity\ProprieterTypeActiviter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType; // Ou TextType si tu changes ta BD

class DataActiviterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('proprieterActiviter', EntityType::class, [
                'class' => ProprieterTypeActiviter::class,
                'choice_label' => 'nomProprieter', // Ou ce qui identifie la propriété
                'placeholder' => 'Choisir une propriété',
            ])
            ->add('valeur', IntegerType::class, [ // Envisage TextType si tu changes la colonne en VARCHAR/TEXT
                 'label' => 'Valeur',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DataActiviter::class,
        ]);
    }
}