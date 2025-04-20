<?php

namespace App\Entity;

use App\Repository\DataActiviterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DataActiviterRepository::class)]
class DataActiviter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $valeur = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProprieterTypeActiviter $ProprieterActiviter = null;

    #[ORM\ManyToOne(inversedBy: 'dataActiviters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ActiviterExercice $activiterExercice = null;


    public function __toString(): string
    {
        if ($this->ProprieterActiviter->getNomProprieter() === "durée"){
            return  $this->valeur  . ' minutes' ;

        }
        return  $this->valeur  . ' '. $this->ProprieterActiviter ;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeur(): ?int
    {
        return $this->valeur;
    }

    public function setValeur(int $valeur): static
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getProprieterActiviter(): ?ProprieterTypeActiviter
    {
        return $this->ProprieterActiviter;
    }

    public function setProprieterActiviter(?ProprieterTypeActiviter $ProprieterActiviter): static
    {
        $this->ProprieterActiviter = $ProprieterActiviter;

        return $this;
    }

    public function getActiviterExercice(): ?ActiviterExercice
    {
        return $this->activiterExercice;
    }

    public function setActiviterExercice(?ActiviterExercice $activiterExercice): static
    {
        $this->activiterExercice = $activiterExercice;

        return $this;
    }
}
