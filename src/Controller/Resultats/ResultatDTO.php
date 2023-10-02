<?php
/** src/Controller/ResultatController.php */ 
namespace App\Controller\Resultats;

use App\Entity\Game;
use App\Entity\Guilde;
use App\Entity\Joueur;
use App\Entity\MissionControle;
use App\Entity\MissionCombat;
use App\Entity\Personnage;
use App\Entity\Tournoi;

class ResultatDTO
{

    private ?int $__gameId = null;
    private ?\DateTimeInterface $_date = null;
    private Joueur $_joueur1;
    private Joueur $_joueur2;
    private ?bool $_vainqueur1;
    private ?bool $_vainqueur2;
    private ?int $_scoreJoueur1;
    private ?int $_scoreJoueur2;
    private ?bool $rixe;
    private ?Tournoi $__tournoi;
    private ?int $__ronde;
    private MissionControle $_missionControle;
    private MissionCombat $_missionCombat;
    private Guilde $_guilde1;
    private Guilde $_guilde2;
    private ?Personnage $_personnage1Joueur1 = null;
    private ?Personnage $_personnage2Joueur1 = null;
    private ?Personnage $_personnage3Joueur1 = null;
    private ?Personnage $_personnage4Joueur1 = null;
    private ?Personnage $_personnage5Joueur1 = null;
    private ?Personnage $_personnage1Joueur2 = null;
    private ?Personnage $_personnage2Joueur2 = null;
    private ?Personnage $_personnage3Joueur2 = null;
    private ?Personnage $_personnage4Joueur2 = null;
    private ?Personnage $_personnage5Joueur2 = null;


    public function __construct(?Game $game){
        
        if($game == null){
            return;
        }

        $this->setGameId($game->getId());
        $this->setDate($game->getDate());
        $this->setJoueur1($game->getBelligerant1()->getJoueur());
        $this->setJoueur2($game->getBelligerant2()->getJoueur());
        $this->setGuilde1($game->getBelligerant1()->getCompo()->getGuilde());
        $this->setGuilde2($game->getBelligerant2()->getCompo()->getGuilde());
        $this->setVainqueur1($game->getBelligerant1()->isVainqueur());
        $this->setVainqueur2($game->getBelligerant2()->isVainqueur());
        $this->setScoreJoueur1($game->getBelligerant1()->getScore());
        $this->setScoreJoueur2($game->getBelligerant2()->getScore());
        $this->setRixe($game->isRixe());
        $this->setTournoi($game->getTournoi());
        $this->setRonde($game->getRonde());
        $this->setMissionControle($game->getMissionControle());
        $this->setMissionCombat($game->getMissionCombat());
        $this->setPersonnage1Joueur1($game->getBelligerant1()->getCompo()->getPersonnages()->get(0));
        $this->setPersonnage2Joueur1($game->getBelligerant1()->getCompo()->getPersonnages()->get(1));
        $this->setPersonnage3Joueur1($game->getBelligerant1()->getCompo()->getPersonnages()->get(2));
        $this->setPersonnage4Joueur1($game->getBelligerant1()->getCompo()->getPersonnages()->get(3));
        $this->setPersonnage5Joueur1($game->getBelligerant1()->getCompo()->getPersonnages()->get(4));
        $this->setPersonnage1Joueur2($game->getBelligerant2()->getCompo()->getPersonnages()->get(0));
        $this->setPersonnage2Joueur2($game->getBelligerant2()->getCompo()->getPersonnages()->get(1));
        $this->setPersonnage3Joueur2($game->getBelligerant2()->getCompo()->getPersonnages()->get(2));
        $this->setPersonnage4Joueur2($game->getBelligerant2()->getCompo()->getPersonnages()->get(3));
        $this->setPersonnage5Joueur2($game->getBelligerant2()->getCompo()->getPersonnages()->get(4));
    }


    /**
     * Get the value of __gameId
     */
    public function getGameId(): ?int
    {
        return $this->__gameId;
    }

    /**
     * Set the value of __gameId
     */
    public function setGameId(?int $__gameId): self
    {
        $this->__gameId = $__gameId;

        return $this;
    }

    /**
     * Get the value of _date
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->_date;
    }

    /**
     * Set the value of _date
     */
    public function setDate(?\DateTimeInterface $_date): self
    {
        $this->_date = $_date;

        return $this;
    }

    /**
     * Get the value of _joueur1
     */
    public function getJoueur1(): Joueur
    {
        return $this->_joueur1;
    }

    /**
     * Set the value of _joueur1
     */
    public function setJoueur1(Joueur $_joueur1): self
    {
        $this->_joueur1 = $_joueur1;

        return $this;
    }

    /**
     * Get the value of _joueur2
     */
    public function getJoueur2(): Joueur
    {
        return $this->_joueur2;
    }

    /**
     * Set the value of _joueur2
     */
    public function setJoueur2(Joueur $_joueur2): self
    {
        $this->_joueur2 = $_joueur2;

        return $this;
    }

    /**
     * Get the value of _vainqueur1
     */
    public function isVainqueur1(): ?bool
    {
        return $this->_vainqueur1;
    }

    /**
     * Set the value of _vainqueur1
     */
    public function setVainqueur1(?bool $_vainqueur1): self
    {
        $this->_vainqueur1 = $_vainqueur1;

        return $this;
    }

    /**
     * Get the value of _vainqueur2
     */
    public function isVainqueur2(): ?bool
    {
        return $this->_vainqueur2;
    }

    /**
     * Set the value of _vainqueur2
     */
    public function setVainqueur2(?bool $_vainqueur2): self
    {
        $this->_vainqueur2 = $_vainqueur2;

        return $this;
    }

    /**
     * Get the value of _scoreJoueur1
     */
    public function getScoreJoueur1(): ?int
    {
        return $this->_scoreJoueur1;
    }

    /**
     * Set the value of _scoreJoueur1
     */
    public function setScoreJoueur1(?int $_scoreJoueur1): self
    {
        $this->_scoreJoueur1 = $_scoreJoueur1;

        return $this;
    }

    /**
     * Get the value of _scoreJoueur2
     */
    public function getScoreJoueur2(): ?int
    {
        return $this->_scoreJoueur2;
    }

    /**
     * Set the value of _scoreJoueur2
     */
    public function setScoreJoueur2(?int $_scoreJoueur2): self
    {
        $this->_scoreJoueur2 = $_scoreJoueur2;

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
     * Get the value of _missionControle
     */
    public function getMissionControle(): MissionControle
    {
        return $this->_missionControle;
    }

    /**
     * Set the value of _missionControle
     */
    public function setMissionControle(MissionControle $_missionControle): self
    {
        $this->_missionControle = $_missionControle;

        return $this;
    }

    /**
     * Get the value of _missionCombat
     */
    public function getMissionCombat(): MissionCombat
    {
        return $this->_missionCombat;
    }

    /**
     * Set the value of _missionCombat
     */
    public function setMissionCombat(MissionCombat $_missionCombat): self
    {
        $this->_missionCombat = $_missionCombat;

        return $this;
    }

    /**
     * Get the value of _guilde1
     */
    public function getGuilde1(): Guilde
    {
        return $this->_guilde1;
    }

    /**
     * Set the value of _guilde1
     */
    public function setGuilde1(Guilde $_guilde1): self
    {
        $this->_guilde1 = $_guilde1;

        return $this;
    }

    /**
     * Get the value of _guilde2
     */
    public function getGuilde2(): Guilde
    {
        return $this->_guilde2;
    }

    /**
     * Set the value of _guilde2
     */
    public function setGuilde2(Guilde $_guilde2): self
    {
        $this->_guilde2 = $_guilde2;

        return $this;
    }

    /**
     * Get the value of _personnage1Joueur1
     */
    public function getPersonnage1Joueur1(): ?Personnage
    {
        return $this->_personnage1Joueur1;
    }

    /**
     * Set the value of _personnage1Joueur1
     */
    public function setPersonnage1Joueur1(?Personnage $_personnage1Joueur1): self
    {
        $this->_personnage1Joueur1 = $_personnage1Joueur1;

        return $this;
    }

    /**
     * Get the value of _personnage2Joueur1
     */
    public function getPersonnage2Joueur1(): ?Personnage
    {
        return $this->_personnage2Joueur1;
    }

    /**
     * Set the value of _personnage2Joueur1
     */
    public function setPersonnage2Joueur1(?Personnage $_personnage2Joueur1): self
    {
        $this->_personnage2Joueur1 = $_personnage2Joueur1;

        return $this;
    }

    /**
     * Get the value of _personnage3Joueur1
     */
    public function getPersonnage3Joueur1(): ?Personnage
    {
        return $this->_personnage3Joueur1;
    }

    /**
     * Set the value of _personnage3Joueur1
     */
    public function setPersonnage3Joueur1(?Personnage $_personnage3Joueur1): self
    {
        $this->_personnage3Joueur1 = $_personnage3Joueur1;

        return $this;
    }

    /**
     * Get the value of _personnage4Joueur1
     */
    public function getPersonnage4Joueur1(): ?Personnage
    {
        return $this->_personnage4Joueur1;
    }

    /**
     * Set the value of _personnage4Joueur1
     */
    public function setPersonnage4Joueur1(?Personnage $_personnage4Joueur1): self
    {
        $this->_personnage4Joueur1 = $_personnage4Joueur1;

        return $this;
    }

    /**
     * Get the value of _personnage5Joueur1
     */
    public function getPersonnage5Joueur1(): ?Personnage
    {
        return $this->_personnage5Joueur1;
    }

    /**
     * Set the value of _personnage5Joueur1
     */
    public function setPersonnage5Joueur1(?Personnage $_personnage5Joueur1): self
    {
        $this->_personnage5Joueur1 = $_personnage5Joueur1;

        return $this;
    }

    /**
     * Get the value of _personnage1Joueur2
     */
    public function getPersonnage1Joueur2(): ?Personnage
    {
        return $this->_personnage1Joueur2;
    }

    /**
     * Set the value of _personnage1Joueur2
     */
    public function setPersonnage1Joueur2(?Personnage $_personnage1Joueur2): self
    {
        $this->_personnage1Joueur2 = $_personnage1Joueur2;

        return $this;
    }

    /**
     * Get the value of _personnage2Joueur2
     */
    public function getPersonnage2Joueur2(): ?Personnage
    {
        return $this->_personnage2Joueur2;
    }

    /**
     * Set the value of _personnage2Joueur2
     */
    public function setPersonnage2Joueur2(?Personnage $_personnage2Joueur2): self
    {
        $this->_personnage2Joueur2 = $_personnage2Joueur2;

        return $this;
    }

    /**
     * Get the value of _personnage3Joueur2
     */
    public function getPersonnage3Joueur2(): ?Personnage
    {
        return $this->_personnage3Joueur2;
    }

    /**
     * Set the value of _personnage3Joueur2
     */
    public function setPersonnage3Joueur2(?Personnage $_personnage3Joueur2): self
    {
        $this->_personnage3Joueur2 = $_personnage3Joueur2;

        return $this;
    }

    /**
     * Get the value of _personnage4Joueur2
     */
    public function getPersonnage4Joueur2(): ?Personnage
    {
        return $this->_personnage4Joueur2;
    }

    /**
     * Set the value of _personnage4Joueur2
     */
    public function setPersonnage4Joueur2(?Personnage $_personnage4Joueur2): self
    {
        $this->_personnage4Joueur2 = $_personnage4Joueur2;

        return $this;
    }

    /**
     * Get the value of _personnage5Joueur2
     */
    public function getPersonnage5Joueur2(): ?Personnage
    {
        return $this->_personnage5Joueur2;
    }

    /**
     * Set the value of _personnage5Joueur2
     */
    public function setPersonnage5Joueur2(?Personnage $_personnage5Joueur2): self
    {
        $this->_personnage5Joueur2 = $_personnage5Joueur2;

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
     * Get the value of __ronde
     */
    public function getRonde(): ?int
    {
        return $this->__ronde;
    }

    /**
     * Set the value of __ronde
     */
    public function setRonde(?int $__ronde): self
    {
        $this->__ronde = $__ronde;

        return $this;
    }
}
