<?php

namespace App\Entity;

use App\Repository\RessourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Matiere;


#[ORM\Entity(repositoryClass: RessourceRepository::class)]
class Ressource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $state = null;

    #[ORM\Column(length: 10)]
    private ?string $semestre = 'S1';


    #[ORM\ManyToOne(inversedBy: 'ressources')]
    private ?User $referent = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private ?int $heuresSemaine = 0;


    /**
     * @var Collection<int, Matiere>
     */
    #[ORM\ManyToMany(targetEntity: Matiere::class, inversedBy: "ressources")]
    #[ORM\JoinTable(name: "ressource_matiere")]
    private Collection $matieres;


    /**
     * @var Collection<int, Creneau>
     */
    #[ORM\OneToMany(targetEntity: Creneau::class, mappedBy: 'ressource')]
    private Collection $creneaus;


    #[ORM\OneToMany(targetEntity: Ressource::class, mappedBy: 'parentRessource', cascade: ['persist', 'remove'])]
    private Collection $subRessources;

    #[ORM\ManyToOne(targetEntity: Ressource::class, inversedBy: 'subRessources')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Ressource $parentRessource = null;

    /**
     * @var Collection<int, RessourceSemaine>
     */
    #[ORM\OneToMany(targetEntity: RessourceSemaine::class, mappedBy: 'ressource', cascade: ['persist', 'remove'])]
    private Collection $ressourceSemaines;


    public function __construct()
    {
        $this->matieres = new ArrayCollection();
        $this->creneaus = new ArrayCollection();
        $this->subRessources = new ArrayCollection();
        $this->ressourceSemaines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }
    public function getSemestre(): ?string
    {
        return $this->semestre;
    }

    public function setSemestre(string $semestre): static
    {
        $this->semestre = $semestre;
        return $this;
    }


    public function getReferent(): ?User
    {
        return $this->referent;
    }

    public function setReferent(?User $referent): static
    {
        $this->referent = $referent;

        if ($referent && !in_array('ROLE_PROF_REFERENT', $referent->getRoles())) {
            throw new \Exception("L'utilisateur doit être un professeur référent pour être assigné à cette ressource.");
        }

        return $this;
    }

    public function getHeuresSemaine(): ?int
    {
        return $this->heuresSemaine;
    }

    public function setHeuresSemaine(int $heuresSemaine): static
    {
        $this->heuresSemaine = $heuresSemaine;
        return $this;
    }

    /**
     * @return Collection<int, Ressource>
     */
    public function getSubRessources(): Collection
    {
        return $this->subRessources;
    }

    public function addSubRessource(Ressource $subRessource): static
    {
        if (!$this->subRessources->contains($subRessource)) {
            $this->subRessources->add($subRessource);
            $subRessource->setParentRessource($this);
        }
        return $this;
    }

    public function removeSubRessource(Ressource $subRessource): static
    {
        if ($this->subRessources->removeElement($subRessource)) {
            if ($subRessource->getParentRessource() === $this) {
                $subRessource->setParentRessource(null);
            }
        }
        return $this;
    }

    public function getParentRessource(): ?Ressource
    {
        return $this->parentRessource;
    }

    public function setParentRessource(?Ressource $parentRessource): static
    {
        $this->parentRessource = $parentRessource;
        return $this;
    }


    /**
     * @return Collection<int, Creneau>
     */
    public function getCreneaus(): Collection
    {
        return $this->creneaus;
    }

    public function addCreneau(Creneau $creneau): static
    {
        if (!$this->creneaus->contains($creneau)) {
            $this->creneaus->add($creneau);
            $creneau->setRessource($this);
        }

        return $this;
    }

    public function removeCreneau(Creneau $creneau): static
    {
        if ($this->creneaus->removeElement($creneau)) {
            // set the owning side to null (unless already changed)
            if ($creneau->getRessource() === $this) {
                $creneau->setRessource(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection<int, Matiere>
     */
    public function getMatieres(): Collection
    {
        return $this->matieres;
    }

    public function addMatiere(Matiere $matiere): static
    {
        if (!$this->matieres->contains($matiere)) {
            $this->matieres->add($matiere);
            $matiere->addRessource($this);
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): static
    {
        if ($this->matieres->removeElement($matiere)) {
            $matiere->removeRessource($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, RessourceSemaine>
     */
    public function getRessourceSemaines(): Collection
    {
        return $this->ressourceSemaines;
    }

    public function addRessourceSemaine(RessourceSemaine $ressourceSemaine): static
    {
        if (!$this->ressourceSemaines->contains($ressourceSemaine)) {
            $this->ressourceSemaines->add($ressourceSemaine);
            $ressourceSemaine->setRessource($this);
        }

        return $this;
    }

    public function removeRessourceSemaine(RessourceSemaine $ressourceSemaine): static
    {
        if ($this->ressourceSemaines->removeElement($ressourceSemaine)) {
            // set the owning side to null (unless already changed)
            if ($ressourceSemaine->getRessource() === $this) {
                $ressourceSemaine->setRessource(null);
            }
        }

        return $this;
    }


}
