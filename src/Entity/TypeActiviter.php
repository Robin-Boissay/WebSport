<?php

namespace App\Entity;

use App\Repository\TypeActiviterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeActiviterRepository::class)]
class TypeActiviter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomType = null;

    /**
     * @var Collection<int, ProprieterTypeActiviter>
     */
    #[ORM\ManyToMany(targetEntity: ProprieterTypeActiviter::class, inversedBy: 'typeActiviters')]
    private Collection $proprieter;

    /**
     * @var Collection<int, ActiviterExercice>
     */
    #[ORM\OneToMany(targetEntity: ActiviterExercice::class, mappedBy: 'type_activiter', orphanRemoval: true)]
    private Collection $activiterExercices;



    public function __construct()
    {
        $this->proprieter = new ArrayCollection();
        $this->activiterExercices = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->nomType;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomType(): ?string
    {
        return $this->nomType;
    }

    public function setNomType(string $nomType): static
    {
        $this->nomType = $nomType;

        return $this;
    }

    /**
     * @return Collection<int, ProprieterTypeActiviter>
     */
    public function getProprieter(): Collection
    {
        return $this->proprieter;
    }

    public function addProprieter(ProprieterTypeActiviter $proprieter): static
    {
        if (!$this->proprieter->contains($proprieter)) {
            $this->proprieter->add($proprieter);
        }

        return $this;
    }

    public function removeProprieter(ProprieterTypeActiviter $proprieter): static
    {
        $this->proprieter->removeElement($proprieter);

        return $this;
    }

    /**
     * @return Collection<int, ActiviterExercice>
     */
    public function getActiviterExercices(): Collection
    {
        return $this->activiterExercices;
    }

    public function addActiviterExercice(ActiviterExercice $activiterExercice): static
    {
        if (!$this->activiterExercices->contains($activiterExercice)) {
            $this->activiterExercices->add($activiterExercice);
            $activiterExercice->setTypeActiviter($this);
        }

        return $this;
    }

    public function removeActiviterExercice(ActiviterExercice $activiterExercice): static
    {
        if ($this->activiterExercices->removeElement($activiterExercice)) {
            // set the owning side to null (unless already changed)
            if ($activiterExercice->getTypeActiviter() === $this) {
                $activiterExercice->setTypeActiviter(null);
            }
        }

        return $this;
    }
}
