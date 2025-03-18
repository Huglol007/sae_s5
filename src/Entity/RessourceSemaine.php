<?php

namespace App\Entity;

use App\Repository\RessourceSemaineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RessourceSemaineRepository::class)]
class RessourceSemaine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ressourceSemaines')]
    private ?Ressource $ressource = null;

    #[ORM\Column]
    private ?string $semaine = null;

    #[ORM\Column]
    private ?float $cm = null;

    #[ORM\Column]
    private ?float $td = null;

    #[ORM\Column]
    private ?float $tp = null;

    #[ORM\Column]
    private ?float $ds = null;

    #[ORM\Column]
    private ?float $sae = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSemaine(): ?string
    {
        return $this->semaine;
    }

    public function setSemaine(string $semaine): static
    {
        $this->semaine = $semaine;

        return $this;
    }

    public function getCm(): ?float
    {
        return $this->cm;
    }

    public function setCm(float $cm): static
    {
        $this->cm = $cm;

        return $this;
    }

    public function getTd(): ?float
    {
        return $this->td;
    }

    public function setTd(float $td): static
    {
        $this->td = $td;

        return $this;
    }

    public function getTp(): ?float
    {
        return $this->tp;
    }

    public function setTp(float $tp): static
    {
        $this->tp = $tp;

        return $this;
    }

    public function getDs(): ?float
    {
        return $this->ds;
    }

    public function setDs(float $ds): static
    {
        $this->ds = $ds;

        return $this;
    }

    public function getSae(): ?float
    {
        return $this->sae;
    }

    public function setSae(float $sae): static
    {
        $this->sae = $sae;

        return $this;
    }
}
