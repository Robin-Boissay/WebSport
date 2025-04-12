<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Activiter>
     */
    #[ORM\OneToMany(targetEntity: Activiter::class, mappedBy: 'user', orphanRemoval: true)]
    #[ORM\OrderBy(['startedAt' => 'DESC'])]
    private Collection $activiters;

    /**
     * @var Collection<int, Friend>
     */
    #[ORM\OneToMany(targetEntity: Friend::class, mappedBy: 'requester', orphanRemoval: true)]
    private Collection $sentFriendRequests;

    #[ORM\OneToMany(targetEntity: Friend::class, mappedBy: 'receiver', orphanRemoval: true)]
    private Collection $receivedFriendRequests;

    public function __construct()
    {
        $this->activiters = new ArrayCollection();
        $this->sentFriendRequests = new ArrayCollection();
        $this->receivedFriendRequests = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Activiter>
     */
    public function getActiviters(): Collection
    {
        return $this->activiters;
    }

    public function addActiviter(Activiter $activiter): static
    {
        if (!$this->activiters->contains($activiter)) {
            $this->activiters->add($activiter);
            $activiter->setUser($this);
        }

        return $this;
    }

    public function removeActiviter(Activiter $activiter): static
    {
        if ($this->activiters->removeElement($activiter)) {
            // set the owning side to null (unless already changed)
            if ($activiter->getUser() === $this) {
                $activiter->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Friend>
     */
    public function getSentFriendRequests(): Collection
    {
        return $this->sentFriendRequests;
    }

    public function addSentFriendRequests(Friend $friend): static
    {
        if (!$this->sentFriendRequests->contains($friend)) {
            $this->sentFriendRequests->add($friend);
            $friend->setRequester($this);
        }

        return $this;
    }

    public function removeSentFriendRequests(Friend $friend): static
    {
        if ($this->sentFriendRequests->removeElement($friend)) {
            // set the owning side to null (unless already changed)
            if ($friend->getRequester() === $this) {
                $friend->setRequester(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Friend>
     */
    public function getReceivedFriendRequests(): Collection
    {
        return $this->receivedFriendRequests;
    }

    public function addReceivedFriendRequests(Friend $friend): static
    {
        if (!$this->receivedFriendRequests->contains($friend)) {
            $this->receivedFriendRequests->add($friend);
            $friend->setRequester($this);
        }

        return $this;
    }

    public function removeReceivedFriendRequests(Friend $friend): static
    {
        if ($this->receivedFriendRequests->removeElement($friend)) {
            // set the owning side to null (unless already changed)
            if ($friend->getRequester() === $this) {
                $friend->setRequester(null);
            }
        }

        return $this;
    }
}
