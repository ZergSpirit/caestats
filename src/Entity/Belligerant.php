<?php

namespace App\Entity;

use App\Repository\BelligerantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BelligerantRepository::class)]
class Belligerant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'belligerants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Joueur $joueur = null;

    #[ORM\OneToOne(inversedBy: 'belligerant', orphanRemoval: true, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Compo $compo = null;

    #[ORM\Column(nullable: true)]
    private ?bool $vainqueur = null;

    #[ORM\Column(nullable: true)]
    private ?int $score = null;

    public function __construct(?Joueur $joueur = null, ?Guilde $guilde = null){
        $this->setJoueur($joueur);
        if($guilde != null){
            $compo = new Compo();
            $compo->setGuilde($guilde);
            $this->setCompo($compo);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJoueur(): ?Joueur
    {
        return $this->joueur;
    }

    public function setJoueur(?Joueur $joueur): static
    {
        $this->joueur = $joueur;

        return $this;
    }

    public function getCompo(): ?Compo
    {
        return $this->compo;
    }

    public function setCompo(?Compo $compo): static
    {
        $this->compo = $compo;

        return $this;
    }

    public function isVainqueur(): ?bool
    {
        return $this->vainqueur;
    }

    public function setVainqueur(?bool $vainqueur): static
    {
        $this->vainqueur = $vainqueur;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): static
    {
        $this->score = $score;

        return $this;
    }
}
