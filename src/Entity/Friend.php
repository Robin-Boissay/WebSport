<?php

namespace App\Entity;

use App\Repository\FriendRepository; // Assure-toi de créer ce repository
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; // Pour la validation
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity; // Autre façon de faire l'unicité

#[ORM\Entity(repositoryClass: FriendRepository::class)]
// Ajout de la contrainte d'unicité au niveau de la table.
// Le nom 'friend_request_unique' est arbitraire mais doit être unique dans la BD.
// 'columns' spécifie les colonnes BDD qui doivent être uniques *ensemble*.
#[ORM\Table(name: 'friend')]
#[ORM\UniqueConstraint(name: 'friend_request_unique', columns: ['requester_id', 'receiver_id'])]
// Alternative à UniqueConstraint via un attribut de validation (vérifie avant le flush)
// #[UniqueEntity(fields: ['requester', 'receiver'], message: 'Une demande d\'ami existe déjà entre ces deux utilisateurs.')]

// Ajout d'une validation pour empêcher qu'un user soit ami avec lui-même
#[Assert\Expression(
    "this.getRequester() !== this.getReceiver()",
    message: "Un utilisateur ne peut pas être ami avec lui-même."
)]
class Friend
{
    // Constantes pour les statuts (ou utiliser une Enum PHP 8.1+)
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_DECLINED = 'declined';
    public const STATUS_BLOCKED = 'blocked'; // Optionnel

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)] // Supposant que ton entité User existe
    #[ORM\JoinColumn(nullable: false, name: "requester_id")] // Assure la liaison BDD
    #[Assert\NotNull]
    private ?User $requester = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, name: "receiver_id")] // Assure la liaison BDD
    #[Assert\NotNull]
    private ?User $receiver = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: [self::STATUS_PENDING, self::STATUS_ACCEPTED, self::STATUS_DECLINED, self::STATUS_BLOCKED])]
    private ?string $status = self::STATUS_PENDING; // Statut par défaut

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $requested_at = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)] // Accepté peut être null
    private ?\DateTimeImmutable $accepted_at = null;

    public function __construct()
    {
        // Pré-remplir la date de demande lors de la création
        $this->requested_at = new \DateTimeImmutable();
        $this->status = self::STATUS_PENDING; // Assurer le statut par défaut
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequester(): ?User
    {
        return $this->requester;
    }

    public function setRequester(?User $requester): static
    {
        $this->requester = $requester;
        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): static
    {
        $this->receiver = $receiver;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        // Optionnel : Ajouter une validation ici pour s'assurer que le statut est valide
        if (!in_array($status, [self::STATUS_PENDING, self::STATUS_ACCEPTED, self::STATUS_DECLINED, self::STATUS_BLOCKED])) {
            throw new \InvalidArgumentException("Invalid status provided");
        }
        $this->status = $status;
        return $this;
    }

    public function setRequestedAt(?\DateTimeImmutable $requested_at): static
    {
        $this->requested_at = $requested_at;
        return $this;
    }
    public function getRequestedAt(): ?\DateTimeImmutable
    {
        return $this->requested_at;
    }

    // On ne définit généralement pas de setter pour requested_at car il est défini à la construction

    public function getAcceptedAt(): ?\DateTimeImmutable
    {
        return $this->accepted_at;
    }

    public function setAcceptedAt(?\DateTimeImmutable $accepted_at): static
    {
        $this->accepted_at = $accepted_at;
        return $this;
    }

     // Helper method to easily check status (optional)
    public function isAccepted(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}