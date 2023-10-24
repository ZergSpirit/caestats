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

    #[ORM\OneToMany(mappedBy: 'vainqueur', targetEntity: Game::class)]
    private Collection $games;

    #[ORM\OneToMany(mappedBy: 'joueur', targetEntity: Rank::class, orphanRemoval: true)]
    private Collection $ranks;

    #[ORM\Column(nullable: true)]
    private ?int $zits = null;

    public function __construct()
    {
        $this->belligerants = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->ranks = new ArrayCollection();
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

    /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): static
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
            $game->setVainqueur($this);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getVainqueur() === $this) {
                $game->setVainqueur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rank>
     */
    public function getRanks(): Collection
    {
        return $this->ranks;
    }

    public function addRank(Rank $rank): static
    {
        if (!$this->ranks->contains($rank)) {
            $this->ranks->add($rank);
            $rank->setJoueur($this);
        }

        return $this;
    }

    public function removeRank(Rank $rank): static
    {
        if ($this->ranks->removeElement($rank)) {
            // set the owning side to null (unless already changed)
            if ($rank->getJoueur() === $this) {
                $rank->setJoueur(null);
            }
        }

        return $this;
    }

    public function getZits(): ?int
    {
        return $this->zits;
    }

    public function setZits(?int $zits): static
    {
        $this->zits = $zits;

        return $this;
    }
}
