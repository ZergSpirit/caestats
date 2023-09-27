<?php

namespace App\Entity;

use App\Repository\PersonnageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonnageRepository::class)]
class Personnage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $Code = null;

    #[ORM\Column]
    private ?bool $ethere = null;

    #[ORM\ManyToMany(targetEntity: Guilde::class, inversedBy: 'personnages')]
    private Collection $guildes;

    public function __construct()
    {
        $this->guildes = new ArrayCollection();
    }
    
    public function __toString() {
        return$this->Code;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->Code;
    }

    public function setCode(string $Code): static
    {
        $this->Code = $Code;

        return $this;
    }

    /**
     * @return Collection<int, Guilde>
     */
    public function getGuildes(): Collection
    {
        return $this->guildes;
    }

    public function addGuilde(Guilde $guilde): static
    {
        if (!$this->guildes->contains($guilde)) {
            $this->guildes->add($guilde);
        }

        return $this;
    }

    public function removeGuilde(Guilde $guilde): static
    {
        $this->guildes->removeElement($guilde);

        return $this;
    }

    public function isEthere(): ?bool
    {
        return $this->ethere;
    }

    public function setEthere(bool $ethere): static
    {
        $this->ethere = $ethere;

        return $this;
    }
}
