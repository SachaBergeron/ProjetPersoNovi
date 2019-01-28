<?php

namespace App\Entity;

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
    private $vol_horaire;

    /**
     * @ORM\Column(type="float")
     */
    private $coefficient;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Prof", inversedBy="modules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $enseignant;

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

    public function getVolHoraire(): ?int
    {
        return $this->vol_horaire;
    }

    public function setVolHoraire(int $vol_horaire): self
    {
        $this->vol_horaire = $vol_horaire;

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
}
