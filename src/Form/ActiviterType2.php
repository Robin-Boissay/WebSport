<?php

namespace App\Form;

use App\Entity\Activiter;
use App\Entity\ProprieterTypeActiviter;
use App\Entity\TypeActiviter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;
class ActiviterType2 extends AbstractType
{
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add("nomActiviter", TextType::class,[
            "label" => "Nom de l'activiter",
        ])->add("typeActiviter", EntityType::class,[
            "label" => "Type de l'activiter",
            "class" => TypeActiviter::class,
        ])->add("save", SubmitType::class,[]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activiter::class,
        ]);
    }

}
