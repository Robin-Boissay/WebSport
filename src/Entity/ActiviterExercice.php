<?php

namespace App\Entity;

use App\Repository\ActiviterExerciceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActiviterExerciceRepository::class)]
class ActiviterExercice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'activiterExercices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Activiter $activiterId = null;

    #[ORM\ManyToOne(inversedBy: 'activiterExercices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeActiviter $type_activiter = null;

    /**
     * @var Collection<int, DataActiviter>
     */
    #[ORM\OneToMany(targetEntity: DataActiviter::class, mappedBy: 'activiterExercice', orphanRemoval: true)]
    private Collection $dataActiviters;

    public function __construct()
    {
        $this->dataActiviters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActiviterId(): ?activiter
    {
        return $this->activiterId;
    }

    public function setActiviterId(?activiter $activiterId): static
    {
        $this->activiterId = $activiterId;

        return $this;
    }

    public function getTypeActiviter(): ?typeActiviter
    {
        return $this->type_activiter;
    }

    public function setTypeActiviter(?typeActiviter $type_activiter): static
    {
        $this->type_activiter = $type_activiter;

        return $this;
    }

    /**
     * @return Collection<int, DataActiviter>
     */
    public function getDataActiviters(): Collection
    {
        return $this->dataActiviters;
    }

    public function addDataActiviter(DataActiviter $dataActiviter): static
    {
        if (!$this->dataActiviters->contains($dataActiviter)) {
            $this->dataActiviters->add($dataActiviter);
            $dataActiviter->setActiviterExercice($this);
        }

        return $this;
    }

    public function removeDataActiviter(DataActiviter $dataActiviter): static
    {
        if ($this->dataActiviters->removeElement($dataActiviter)) {
            // set the owning side to null (unless already changed)
            if ($dataActiviter->getActiviterExercice() === $this) {
                $dataActiviter->setActiviterExercice(null);
            }
        }

        return $this;
    }
}
