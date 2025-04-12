<?php

namespace App\Form;

use App\Entity\Activiter;
use App\Entity\DataActiviter;
use App\Entity\ProprieterTypeActiviter;
use App\Entity\TypeActiviter;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActiviterType extends AbstractType
{
    public function __construct(private EntityManagerInterface $entityManager, private Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->DataActiviter = $this->entityManager->getRepository(DataActiviter::class);
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $activiter = $options['data'];
        $typeActiviter = $activiter->getTypeActiviter();

        if (!$typeActiviter) {
            throw new \InvalidArgumentException('Le type d\'activité est requis.');
        }

        // Récupérer toutes les propriétés pour ce TypeActiviter
        $proprieterRepo = $this->entityManager->getRepository(ProprieterTypeActiviter::class);
        $proprieters = $proprieterRepo->getProprieterTypesForTypeActivity($typeActiviter);

        foreach ($proprieters as $proprieter) {
            $builder->add("proprieter_" . $proprieter->getId(), TextType::class, [
                'label' => $proprieter->getNomProprieter(), // Adapte selon ton entité
                'required' => false,
                'mapped' => false, // Empêche l’association automatique avec l’entité Activiter
            ]);
        }

        $builder->add('save', SubmitType::class, [
            'label' => 'Enregistrer l\'activité'
        ]);

        // Gestion des données au submit
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($proprieters) {
            $form = $event->getForm();
            $activiter = $event->getData();
            $entityManager = $this->entityManager;

            foreach ($proprieters as $proprieter) {
                $fieldName = "proprieter_" . $proprieter->getId();
                $value = $form->get($fieldName)->getData();

                if ($value !== null) {
                    $dataActiviter = new DataActiviter();
                    $dataActiviter->setActiviter($activiter);
                    $dataActiviter->setProprieterActiviter($proprieter);
                    $dataActiviter->setValeur($value); // Adapte selon ton entité

                    $entityManager->persist($dataActiviter);
                }
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activiter::class,
        ]);
    }
}
