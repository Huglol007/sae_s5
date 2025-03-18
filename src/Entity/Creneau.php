<?php

namespace App\Entity;

use App\Repository\CreneauRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CreneauRepository::class)]
class Creneau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $start_date;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $end_date = null;

    #[ORM\Column(nullable: true)]
    private ?int $duree = null;

    #[ORM\Column(type: 'string', length: 50, options: ['default' => 'cours'])]
    private string $type = 'cours';


    #[ORM\ManyToOne(inversedBy: 'creneaux')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Matiere $matiere = null;

    #[ORM\ManyToOne(inversedBy: 'creneaux')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $enseignant = null;

    #[ORM\ManyToOne(inversedBy: 'creneaux')]
    private ?Ressource $ressource = null;

    #[ORM\ManyToOne(inversedBy: 'creneaux')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Promotion $promotion = null;

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getStartDate(): \DateTime
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTime $start_date): self
    {
        $this->start_date = $start_date;
        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->end_date;
    }

    public function setEndDate(?\DateTime $end_date): self
    {
        $this->end_date = $end_date;
        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): static
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getEnseignant(): ?User
    {
        return $this->enseignant;
    }

    public function setEnseignant(?User $enseignant): static
    {
        $this->enseignant = $enseignant;

        return $this;
    }

    public function getRessource(): ?Ressource
    {
        return $this->ressource;
    }

    public function setRessource(?Ressource $ressource): static
    {
        $this->ressource = $ressource;

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): static
    {
        $this->promotion = $promotion;

        return $this;
    }
}
