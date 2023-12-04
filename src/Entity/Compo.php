<?php

namespace App\Entity;

use App\Repository\CompoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
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

    #[ORM\OneToOne(mappedBy: 'compo', targetEntity: Belligerant::class)]
    private Belligerant $belligerant;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $code = null;

    #[ORM\Column]
    private bool $noStats = false;

    #[ORM\PreUpdate]
    public function setCurrentCodeValue_update(): void
    {
        $this->updateCode();
    }
    #[ORM\PrePersist]
    public function setCurrentCodeValue_persist(): void
    {
        $this->updateCode();
    }

    private function updateCode(){
        $arrayCompo = $this->getPersonnages()->toArray();
        usort($arrayCompo, fn($a, $b) => strcmp($a, $b));
        $this->setCode(strtoupper($this->getGuilde()->getCode().'_'.implode('-', $arrayCompo)));
    }

    public function __construct()
    {
        $this->personnages = new ArrayCollection();
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

    public function isNoStats(): ?bool
    {
        return $this->noStats;
    }

    public function setNoStats(bool $noStats): static
    {
        $this->noStats = $noStats;

        return $this;
    }


    /**
     * Get the value of belligerant
     */
    public function getBelligerant(): Belligerant
    {
        return $this->belligerant;
    }

    /**
     * Set the value of belligerant
     */
    public function setBelligerant(Belligerant $belligerant): self
    {
        $this->belligerant = $belligerant;

        return $this;
    }
}
