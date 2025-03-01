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



    public function __construct()
    {
        $this->proprieter = new ArrayCollection();
        $this->proprieterTypeActiviters = new ArrayCollection();
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
     * @return Collection<int, ProprieterTypeActiviter>
     */
    public function getProprieterTypeActiviters(): Collection
    {
        return $this->proprieterTypeActiviters;
    }

    public function addProprieterTypeActiviter(ProprieterTypeActiviter $proprieterTypeActiviter): static
    {
        if (!$this->proprieterTypeActiviters->contains($proprieterTypeActiviter)) {
            $this->proprieterTypeActiviters->add($proprieterTypeActiviter);
            $proprieterTypeActiviter->addTypeActiviter($this);
        }

        return $this;
    }

    public function removeProprieterTypeActiviter(ProprieterTypeActiviter $proprieterTypeActiviter): static
    {
        if ($this->proprieterTypeActiviters->removeElement($proprieterTypeActiviter)) {
            $proprieterTypeActiviter->removeTypeActiviter($this);
        }

        return $this;
    }
}
