<?php

namespace App\Entity;

use App\Repository\ProprieterTypeActiviterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProprieterTypeActiviterRepository::class)]
class ProprieterTypeActiviter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomProprieter = null;

    /**
     * @var Collection<int, TypeActiviter>
     */
    #[ORM\ManyToMany(targetEntity: TypeActiviter::class, mappedBy: 'proprieter')]
    private Collection $typeActiviters;


    public function __construct()
    {
        $this->typeActiviters = new ArrayCollection();
        $this->TypeActiviter = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->nomProprieter;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProprieter(): ?string
    {
        return $this->nomProprieter;
    }

    public function setNomProprieter(string $nomProprieter): static
    {
        $this->nomProprieter = $nomProprieter;

        return $this;
    }

    /**
     * @return Collection<int, TypeActiviter>
     */
    public function getTypeActiviters(): Collection
    {
        return $this->typeActiviters;
    }

    public function addTypeActiviter(TypeActiviter $typeActiviter): static
    {
        if (!$this->typeActiviters->contains($typeActiviter)) {
            $this->typeActiviters->add($typeActiviter);
            $typeActiviter->addProprieter($this);
        }

        return $this;
    }

    public function removeTypeActiviter(TypeActiviter $typeActiviter): static
    {
        if ($this->typeActiviters->removeElement($typeActiviter)) {
            $typeActiviter->removeProprieter($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, TypeActiviter>
     */
    public function getTypeActiviter(): Collection
    {
        return $this->TypeActiviter;
    }
}
