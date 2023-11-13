<?php
namespace App\Controller;

use App\Entity\MissionCombat;
use App\Entity\MissionControle;
use App\Entity\Tournoi;
use Doctrine\Common\Collections\ArrayCollection;

class TournoiDTO
{

    private ?int $id = null;
    private ?bool $useSeeds = null;
    private ArrayCollection $joueurs;
    private ?bool $rixe = null;
    private ?MissionControle $missionControle = null;
    private ?MissionCombat $missionCombat = null;


    public function __construct(?Tournoi $tournoi = null){
        if($tournoi == null){
            return;
        }
        $this->setId($tournoi->getId());
        $joueurCollection = new ArrayCollection();
        foreach($tournoi->getGames() as $game){
            if($game->getRonde() == 1){
                $joueurCollection->add($game->getBelligerant1()->getJoueur());
                $joueurCollection->add($game->getBelligerant2()->getJoueur());
            }
        }
        $this->setJoueurs($joueurCollection);
        $this->setUseSeeds($tournoi->isUseSeeds());
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
     * Get the value of useSeeds
     */
    public function isUseSeeds(): ?bool
    {
        return $this->useSeeds;
    }

    /**
     * Set the value of useSeeds
     */
    public function setUseSeeds(?bool $useSeeds): self
    {
        $this->useSeeds = $useSeeds;

        return $this;
    }


    /**
     * Get the value of joueurs
     */
    public function getJoueurs(): ArrayCollection
    {
        return $this->joueurs;
    }

    /**
     * Set the value of joueurs
     */
    public function setJoueurs(ArrayCollection $joueurs): self
    {
        $this->joueurs = $joueurs;

        return $this;
    }

    /**
     * Get the value of rixe
     */
    public function isRixe(): ?bool
    {
        return $this->rixe;
    }

    /**
     * Set the value of rixe
     */
    public function setRixe(?bool $rixe): self
    {
        $this->rixe = $rixe;

        return $this;
    }

    /**
     * Get the value of missionCombat
     */
    public function getMissionCombat(): ?MissionCombat
    {
        return $this->missionCombat;
    }

    /**
     * Set the value of missionCombat
     */
    public function setMissionCombat(?MissionCombat $missionCombat): self
    {
        $this->missionCombat = $missionCombat;

        return $this;
    }

    /**
     * Get the value of missionControle
     */
    public function getMissionControle(): ?MissionControle
    {
        return $this->missionControle;
    }

    /**
     * Set the value of missionControle
     */
    public function setMissionControle(?MissionControle $missionControle): self
    {
        $this->missionControle = $missionControle;

        return $this;
    }
}