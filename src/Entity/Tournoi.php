<?php

namespace App\Entity;

use App\Repository\TournoiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

#[ORM\Entity(repositoryClass: TournoiRepository::class)]
class Tournoi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ville = null;

    #[ORM\OneToMany(mappedBy: 'tournoi', targetEntity: Game::class)]
    private Collection $games;

    #[ORM\Column(nullable: false)]
    private ?bool $online = null;

    #[ORM\OneToMany(mappedBy: 'tournoi', targetEntity: Rank::class, orphanRemoval: true)]
    #[OrderBy(["position" => "ASC"])]
    private Collection $ranks;

    #[ORM\Column(name:'zits_cote', nullable: true)]
    private ?int $zitsCote = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbParticipants = null;

    public function __construct()
    {
        $this->games = new ArrayCollection();
        $this->ranks = new ArrayCollection();
    }

  

    /**
     * Get the value of id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

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
     * Get the value of date
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Set the value of date
     */
    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of ville
     */
    public function getVille(): ?string
    {
        return $this->ville;
    }

    /**
     * Set the value of ville
     */
    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get the value of games
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    /**
     * Set the value of games
     */
    public function setGames(Collection $games): self
    {
        $this->games = $games;

        return $this;
    }

    public function isOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(?bool $online): static
    {
        $this->online = $online;

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
            $rank->setTournoi($this);
        }

        return $this;
    }

    public function removeRank(Rank $rank): static
    {
        if ($this->ranks->removeElement($rank)) {
            // set the owning side to null (unless already changed)
            if ($rank->getTournoi() === $this) {
                $rank->setTournoi(null);
            }
        }

        return $this;
    }

    public function getZitsCote(): ?int
    {
        return $this->zitsCote;
    }

    public function setZitsCote(?int $zitsCote): static
    {
        $this->zitsCote = $zitsCote;

        return $this;
    }

    public function getNbParticipants(): ?int
    {
        return $this->nbParticipants;
    }

    public function setNbParticipants(?int $nbParticipants): static
    {
        $this->nbParticipants = $nbParticipants;

        return $this;
    }
}
