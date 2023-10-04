<?php

namespace App\Entity;

use App\Repository\EloLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EloLogRepository::class)]
class EloLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'eloLog', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\Column]
    private ?int $previousEloJoueur1 = null;

    #[ORM\Column]
    private ?int $previousEloJoueur2 = null;

    #[ORM\Column]
    private ?int $variationEloJoueur1 = null;

    #[ORM\Column]
    private ?int $variationEloJoueur2 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getPreviousEloJoueur1(): ?int
    {
        return $this->previousEloJoueur1;
    }

    public function setPreviousEloJoueur1(int $previousEloJoueur1): static
    {
        $this->previousEloJoueur1 = $previousEloJoueur1;

        return $this;
    }

    public function getPreviousEloJoueur2(): ?int
    {
        return $this->previousEloJoueur2;
    }

    public function setPreviousEloJoueur2(int $previousEloJoueur2): static
    {
        $this->previousEloJoueur2 = $previousEloJoueur2;

        return $this;
    }

    public function getVariationEloJoueur1(): ?int
    {
        return $this->variationEloJoueur1;
    }

    public function setVariationEloJoueur1(int $variationEloJoueur1): static
    {
        $this->variationEloJoueur1 = $variationEloJoueur1;

        return $this;
    }

    public function getVariationEloJoueur2(): ?int
    {
        return $this->variationEloJoueur2;
    }

    public function setVariationEloJoueur2(int $variationEloJoueur2): static
    {
        $this->variationEloJoueur2 = $variationEloJoueur2;

        return $this;
    }
}
