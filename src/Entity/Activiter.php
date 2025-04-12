<?php

namespace App\Entity;

use App\Repository\ActiviterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

#[ORM\Entity(repositoryClass: ActiviterRepository::class)]
class Activiter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomActiviter = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'activiters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeActiviter $typeActiviter = null;

    /**
     * @var Collection<int, DataActiviter>
     */
    #[ORM\OneToMany(targetEntity: DataActiviter::class, mappedBy: 'activiter', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $donnees;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startedAt = null;

    public function __construct()
    {
        $this->donnees = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->nomActiviter;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomActiviter(): ?string
    {
        return $this->nomActiviter;
    }

    public function setNomActiviter(string $nomActiviter): static
    {
        $this->nomActiviter = $nomActiviter;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTypeActiviter(): ?TypeActiviter
    {
        return $this->typeActiviter;
    }

    public function setTypeActiviter(?TypeActiviter $typeActiviter): static
    {
        $this->typeActiviter = $typeActiviter;

        return $this;
    }

    /**
     * @return Collection<int, DataActiviter>
     */
    public function getDonnees(): Collection
    {
        return $this->donnees;
    }

    public function setDonnees(Collection $donnees): self
    {
        $this->donnees = $donnees;

        return $this;
    }


    public function addDonnees(DataActiviter $donnee): self
    {
        if (!$this->donnees->contains($donnee)) {
            $this->donnees->add($donnee);
            $donnee->setActiviter($this);
        }

        return $this;
    }

    public function removeDonnees(DataActiviter $donnee): static
    {
        if ($this->donnees->removeElement($donnee)) {
            // set the owning side to null (unless already changed)
            if ($donnee->getActiviter() === $this) {
                $donnee->setActiviter(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }
}
