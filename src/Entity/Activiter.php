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

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startedAt = null;

    /**
     * @var Collection<int, ActiviterExercice>
     */
    #[ORM\OneToMany(targetEntity: ActiviterExercice::class, mappedBy: 'activiterId', orphanRemoval: true)]
    private Collection $activiterExercices;

    public function __construct()
    {
        $this->activiterExercices = new ArrayCollection();
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
            $activiterExercice->setActiviterId($this);
        }

        return $this;
    }

    public function removeActiviterExercice(ActiviterExercice $activiterExercice): static
    {
        if ($this->activiterExercices->removeElement($activiterExercice)) {
            // set the owning side to null (unless already changed)
            if ($activiterExercice->getActiviterId() === $this) {
                $activiterExercice->setActiviterId(null);
            }
        }

        return $this;
    }
}
