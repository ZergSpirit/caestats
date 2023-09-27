<?php

namespace App\Entity;

use App\Repository\CompoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompoRepository::class)]
class Compo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Personnage::class)]
    private Collection $personnages;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Guilde $guilde = null;

    #[ORM\OneToMany(mappedBy: 'compo', targetEntity: Belligerant::class)]
    private Collection $belligerants;

    #[ORM\Column(length: 255)]
    private ?string $Code = null;

    public function __construct()
    {
        $this->personnages = new ArrayCollection();
        $this->belligerants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Personnage>
     */
    public function getPersonnages(): Collection
    {
        return $this->personnages;
    }

    public function addPersonnage(Personnage $personnage): static
    {
        if (!$this->personnages->contains($personnage)) {
            $this->personnages->add($personnage);
        }

        return $this;
    }

    public function removePersonnage(Personnage $personnage): static
    {
        $this->personnages->removeElement($personnage);

        return $this;
    }

    public function getGuilde(): ?Guilde
    {
        return $this->guilde;
    }

    public function setGuilde(?Guilde $guilde): static
    {
        $this->guilde = $guilde;

        return $this;
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
            $belligerant->setCompo($this);
        }

        return $this;
    }

    public function removeBelligerant(Belligerant $belligerant): static
    {
        if ($this->belligerants->removeElement($belligerant)) {
            // set the owning side to null (unless already changed)
            if ($belligerant->getCompo() === $this) {
                $belligerant->setCompo(null);
            }
        }

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
}
