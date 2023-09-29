<?php

namespace App\Entity;

use App\Repository\PersonnageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: PersonnageRepository::class)]
class Personnage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column]
    private ?bool $ethere = null;

    #[ORM\ManyToMany(targetEntity: Guilde::class, inversedBy: 'personnages')]
    #[Ignore]
    private Collection $guildes;

    public function __construct()
    {
        $this->guildes = new ArrayCollection();
    }
    
    public function __toString() {
        return$this->code;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * Get the value of code
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Set the value of code
     */
    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get the value of nom
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * Set the value of nom
     */
    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }
}
