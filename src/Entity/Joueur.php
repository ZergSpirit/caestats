<?php

namespace App\Entity;

use App\Repository\JoueurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JoueurRepository::class)]
class Joueur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'joueur', targetEntity: Belligerant::class)]
    private Collection $belligerants;

    #[ORM\Column(nullable: true)]
    private ?int $elo = null;

    public function __construct()
    {
        $this->belligerants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Belligerant>
     */
    public function getBelligerants(): Collection
    {
        return $this->belligerants;
    }

    public function addBelligerant(Belligerant $belligerant): static
    {
        if (!$this->belligerants->contains($belligerant)) {
            $this->belligerants->add($belligerant);
            $belligerant->setJoueur($this);
        }

        return $this;
    }

    public function removeBelligerant(Belligerant $belligerant): static
    {
        if ($this->belligerants->removeElement($belligerant)) {
            // set the owning side to null (unless already changed)
            if ($belligerant->getJoueur() === $this) {
                $belligerant->setJoueur(null);
            }
        }

        return $this;
    }

    public function getElo(): ?int
    {
        return $this->elo;
    }

    public function setElo(?int $elo): static
    {
        $this->elo = $elo;

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
