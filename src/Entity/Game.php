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

    #[ORM\OneToOne(inversedBy: 'game', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Belligerant $Belligerant1 = null;

    #[ORM\OneToOne(inversedBy: 'game', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Belligerant $Belligerant2 = null;

    #[ORM\Column]
    private ?bool $rixe = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?MissionControle $missionControle = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?MissionCombat $missionCombat = null;

    #[ORM\Column(nullable: true)]
    private ?int $ronde = null;

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

    public function getBelligerant1(): ?Belligerant
    {
        return $this->Belligerant1;
    }

    public function setBelligerant1(Belligerant $Belligerant1): static
    {
        $this->Belligerant1 = $Belligerant1;

        return $this;
    }

    public function getBelligerant2(): ?Belligerant
    {
        return $this->Belligerant2;
    }

    public function setBelligerant2(Belligerant $Belligerant2): static
    {
        $this->Belligerant2 = $Belligerant2;

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
}
