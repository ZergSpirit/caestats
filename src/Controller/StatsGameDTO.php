<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Guilde;
use App\Entity\Joueur;
use App\Entity\MissionCombat;
use App\Entity\MissionControle;
use App\Entity\Tournoi;
use Doctrine\Common\Collections\ArrayCollection;

class StatsGameDTO
{

    private ?Joueur $_joueur1 = null;
    private ?Joueur $_joueur2 = null;
    private ?bool $_rixe = null;
    private ?Tournoi $__tournoi = null;
    private ?MissionControle $_missionControle = null;
    private ?MissionCombat $_missionCombat = null;
    private ?Guilde $_guilde1 = null;
    private ?Guilde $_guilde2 = null;
    private ?ArrayCollection $_personnageJoueur1 = null;
    private ?ArrayCollection $_personnageJoueur2 = null;
    private ?string $_compoCode = null;

    /**
     * Retourne les belligerants qui matchent avec les critères du DTO
     */
    public function getRelevantBelligerants(Game $game)
    {   
        $belligerants = new ArrayCollection();
        if ($this->getJoueur1() != null) {
            $belligerants->add($game->getBelligerant1()->getJoueur()->getId() == $this->getJoueur1()->getId() ? $game->getBelligerant1() : $game->getBelligerant2());
            return $belligerants;
        }
        if ($this->getPersonnageJoueur1() != null and $this->getPersonnageJoueur1()->count() > 0) {
            $belligerant1 = null;
            $belligerant2 = null;
            foreach ($this->getPersonnageJoueur1() as $perso) {
                if ($game->getBelligerant1()->getCompo()->getPersonnages()->contains($perso)) {
                    $belligerant1 = $game->getBelligerant1();
                } else {
                    $belligerant1 = null;
                }
                if ($game->getBelligerant2()->getCompo()->getPersonnages()->contains($perso)) {
                    $belligerant2 = $game->getBelligerant2();
                } else {
                    $belligerant2 = null;
                }
            }
            if ($belligerant1 != null) {
                $belligerants->add($belligerant1);
            }
            if ($belligerant2 != null) {
                $belligerants->add($belligerant2);
            }
            return $belligerants;
        }
        if ($this->getGuilde1() != null) {
            $belligerant1 = null;
            $belligerant2 = null;
            if ($game->getBelligerant1()->getCompo()->getGuilde()->getId() == $this->getGuilde1()->getId()) {
                $belligerant1 = $game->getBelligerant1();
            }
            if ($game->getBelligerant2()->getCompo()->getGuilde()->getId() == $this->getGuilde1()->getId()) {
                $belligerant2 = $game->getBelligerant2();
            }
            if ($belligerant1 != null) {
                $belligerants->add($belligerant1);
            }
            if ($belligerant2 != null) {
                $belligerants->add($belligerant2);
            }
            return $belligerants;
        }

    }

    /**
     * Get the value of _joueur1
     */
    public function getJoueur1(): ?Joueur
    {
        return $this->_joueur1;
    }

    /**
     * Set the value of _joueur1
     */
    public function setJoueur1(?Joueur $_joueur1): self
    {
        $this->_joueur1 = $_joueur1;

        return $this;
    }

    /**
     * Get the value of _joueur2
     */
    public function getJoueur2(): ?Joueur
    {
        return $this->_joueur2;
    }

    /**
     * Set the value of _joueur2
     */
    public function setJoueur2(?Joueur $_joueur2): self
    {
        $this->_joueur2 = $_joueur2;

        return $this;
    }

    /**
     * Get the value of _rixe
     */
    public function isRixe(): ?bool
    {
        return $this->_rixe;
    }

    /**
     * Set the value of _rixe
     */
    public function setRixe(?bool $_rixe): self
    {
        $this->_rixe = $_rixe;

        return $this;
    }

    /**
     * Get the value of __tournoi
     */
    public function getTournoi(): ?Tournoi
    {
        return $this->__tournoi;
    }

    /**
     * Set the value of __tournoi
     */
    public function setTournoi(?Tournoi $__tournoi): self
    {
        $this->__tournoi = $__tournoi;

        return $this;
    }

    /**
     * Get the value of _missionControle
     */
    public function getMissionControle(): ?MissionControle
    {
        return $this->_missionControle;
    }

    /**
     * Set the value of _missionControle
     */
    public function setMissionControle(?MissionControle $_missionControle): self
    {
        $this->_missionControle = $_missionControle;

        return $this;
    }

    /**
     * Get the value of _missionCombat
     */
    public function getMissionCombat(): ?MissionCombat
    {
        return $this->_missionCombat;
    }

    /**
     * Set the value of _missionCombat
     */
    public function setMissionCombat(?MissionCombat $_missionCombat): self
    {
        $this->_missionCombat = $_missionCombat;

        return $this;
    }

    /**
     * Get the value of _guilde1
     */
    public function getGuilde1(): ?Guilde
    {
        return $this->_guilde1;
    }

    /**
     * Set the value of _guilde1
     */
    public function setGuilde1(?Guilde $_guilde1): self
    {
        $this->_guilde1 = $_guilde1;

        return $this;
    }

    /**
     * Get the value of _guilde2
     */
    public function getGuilde2(): ?Guilde
    {
        return $this->_guilde2;
    }

    /**
     * Set the value of _guilde2
     */
    public function setGuilde2(?Guilde $_guilde2): self
    {
        $this->_guilde2 = $_guilde2;

        return $this;
    }

    /**
     * Get the value of _personnageJoueur1
     */
    public function getPersonnageJoueur1(): ?ArrayCollection
    {
        return $this->_personnageJoueur1;
    }

    /**
     * Set the value of _personnageJoueur1
     */
    public function setPersonnageJoueur1(?ArrayCollection $_personnageJoueur1): self
    {
        $this->_personnageJoueur1 = $_personnageJoueur1;

        return $this;
    }

    /**
     * Get the value of _personnageJoueur2
     */
    public function getPersonnageJoueur2(): ?ArrayCollection
    {
        return $this->_personnageJoueur2;
    }

    /**
     * Set the value of _personnageJoueur2
     */
    public function setPersonnageJoueur2(?ArrayCollection $_personnageJoueur2): self
    {
        $this->_personnageJoueur2 = $_personnageJoueur2;

        return $this;
    }

    /**
     * Get the value of _compoCode
     */
    public function getCompoCode(): ?string
    {
        return $this->_compoCode;
    }

    /**
     * Set the value of _compoCode
     */
    public function setCompoCode(?string $_compoCode): self
    {
        $this->_compoCode = $_compoCode;

        return $this;
    }
}