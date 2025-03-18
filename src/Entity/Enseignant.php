<?php

namespace App\Entity;

use App\Repository\EnseignantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnseignantRepository::class)]
class Enseignant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'enseignant', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'enseignants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeEnseignant $TypeEnseignant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(User $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getTypeEnseignant(): ?TypeEnseignant
    {
        return $this->TypeEnseignant;
    }

    public function setTypeEnseignant(?TypeEnseignant $TypeEnseignant): static
    {
        $this->TypeEnseignant = $TypeEnseignant;

        return $this;
    }
}
