<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModuleRepository")
 */
class Module
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $vol_horaireTD;

    /**
     * @ORM\Column(type="integer")
     */
    private $vol_horaireTP;

    /**
     * @ORM\Column(type="integer")
     */
    private $vol_horaireCM;

    /**
     * @ORM\Column(type="float")
     */
    private $coefficient;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Prof", inversedBy="modules")
     * @ORM\JoinColumn(nullable=true)
     */
    private $enseignant;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Controle", mappedBy="module", orphanRemoval=true)
     */
    private $controles;

    public function __construct()
    {
        $this->controles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getVolHoraireTD(): ?int
    {
        return $this->vol_horaireTD;
    }

    public function setVolHoraireTD(int $vol_horaireTD): self
    {
        $this->vol_horaireTD = $vol_horaireTD;

        return $this;
    }

    public function getVolHoraireTP(): ?int
    {
        return $this->vol_horaireTP;
    }

    public function setVolHoraireTP(int $vol_horaireTP): self
    {
        $this->vol_horaireTP = $vol_horaireTP;

        return $this;
    }

    public function getVolHoraireCM(): ?int
    {
        return $this->vol_horaireCM;
    }

    public function setVolHoraireCM(int $vol_horaireCM): self
    {
        $this->vol_horaireCM = $vol_horaireCM;

        return $this;
    }

    public function getCoefficient(): ?float
    {
        return $this->coefficient;
    }

    public function setCoefficient(float $coefficient): self
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    public function getEnseignant(): ?Prof
    {
        return $this->enseignant;
    }

    public function setEnseignant(?Prof $enseignant): self
    {
        $this->enseignant = $enseignant;

        return $this;
    }

    /**
     * @return Collection|Controle[]
     */
    public function getControles(): Collection
    {
        return $this->controles;
    }

    public function addControle(Controle $controle): self
    {
        if (!$this->controles->contains($controle)) {
            $this->controles[] = $controle;
            $controle->setModule($this);
        }

        return $this;
    }

    public function removeControle(Controle $controle): self
    {
        if ($this->controles->contains($controle)) {
            $this->controles->removeElement($controle);
            // set the owning side to null (unless already changed)
            if ($controle->getModule() === $this) {
                $controle->setModule(null);
            }
        }

        return $this;
    }


//    function __toString()
//    {
//       $this->mod
//    }
}
