<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?Tournoi $tournoi = null;

    #[ORM\OneToOne(orphanRemoval: true, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Belligerant $belligerant1 = null;

    #[ORM\OneToOne(orphanRemoval: true, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Belligerant $belligerant2 = null;

    #[ORM\Column]
    private ?bool $rixe = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?MissionControle $missionControle = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?MissionCombat $missionCombat = null;

    #[ORM\Column(nullable: true)]
    private ?int $ronde = null;

    #[ORM\Column(nullable: true)]
    private ?int $eloChangeJoueur1 = null;

    #[ORM\Column(nullable: true)]
    private ?int $eloChangeJoueur2 = null;

    #[ORM\OneToOne(mappedBy: 'game', cascade: ['persist', 'remove'])]
    private ?EloLog $eloLog = null;

    #[ORM\Column]
    private bool $noRanking = false;

    #[ORM\Column]
    private bool $noStats = false;

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?Joueur $vainqueur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTournoi(): ?Tournoi
    {
        return $this->tournoi;
    }

    public function setTournoi(?Tournoi $tournoi): static
    {
        $this->tournoi = $tournoi;

        return $this;
    }

    public function isRixe(): ?bool
    {
        return $this->rixe;
    }

    public function setRixe(bool $rixe): static
    {
        $this->rixe = $rixe;

        return $this;
    }

    public function getMissionControle(): ?MissionControle
    {
        return $this->missionControle;
    }

    public function setMissionControle(?MissionControle $missionControle): static
    {
        $this->missionControle = $missionControle;

        return $this;
    }

    public function getMissionCombat(): ?MissionCombat
    {
        return $this->missionCombat;
    }

    public function setMissionCombat(?MissionCombat $missionCombat): static
    {
        $this->missionCombat = $missionCombat;

        return $this;
    }

    public function getRonde(): ?int
    {
        return $this->ronde;
    }

    public function setRonde(?int $ronde): static
    {
        $this->ronde = $ronde;

        return $this;
    }

    public function getEloChangeJoueur1(): ?int
    {
        return $this->eloChangeJoueur1;
    }

    public function setEloChangeJoueur1(?int $eloChangeJoueur1): static
    {
        $this->eloChangeJoueur1 = $eloChangeJoueur1;

        return $this;
    }

    public function getEloChangeJoueur2(): ?int
    {
        return $this->eloChangeJoueur2;
    }

    public function setEloChangeJoueur2(?int $eloChangeJoueur2): static
    {
        $this->eloChangeJoueur2 = $eloChangeJoueur2;

        return $this;
    }

    /**
     * Get the value of belligerant1
     */
    public function getBelligerant1(): ?Belligerant
    {
        return $this->belligerant1;
    }

    /**
     * Set the value of belligerant1
     */
    public function setBelligerant1(?Belligerant $belligerant1): self
    {
        $this->belligerant1 = $belligerant1;

        return $this;
    }

    /**
     * Get the value of belligerant2
     */
    public function getBelligerant2(): ?Belligerant
    {
        return $this->belligerant2;
    }

    /**
     * Set the value of belligerant2
     */
    public function setBelligerant2(?Belligerant $belligerant2): self
    {
        $this->belligerant2 = $belligerant2;

        return $this;
    }

    public function getEloLog(): ?EloLog
    {
        return $this->eloLog;
    }

    public function setEloLog(EloLog $eloLog): static
    {
        // set the owning side of the relation if necessary
        if ($eloLog->getGame() !== $this) {
            $eloLog->setGame($this);
        }

        $this->eloLog = $eloLog;

        return $this;
    }

    public function isNoRanking(): ?bool
    {
        return $this->noRanking;
    }

    public function setNoRanking(bool $noRanking): static
    {
        $this->noRanking = $noRanking;

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

    public function getVainqueur(): ?Joueur
    {
        return $this->vainqueur;
    }

    public function setVainqueur(?Joueur $vainqueur): static
    {
        $this->vainqueur = $vainqueur;

        return $this;
    }
}
