<?php

namespace App\Service;

use App\Controller\Resultats\ResultatDTO;
use App\Entity\Belligerant;
use App\Entity\Compo;
use App\Entity\Game;
use App\Entity\Guilde;
use App\Entity\Joueur;
use App\Entity\MissionCombat;
use App\Entity\MissionControle;
use App\Entity\Personnage;
use App\Entity\Tournoi;
use DateInterval;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class GameManager
{

    /**
     * Construct avec injection
     */
    public function __construct(private EntityManagerInterface $entityManager, private EloManager $eloManager)
    {
        
    }


    /**
     * @param $data un resultatDTO
     * @return la $game créée
     */
    public function saveOrUpdate(ResultatDTO $data)
    {
        if ($data->getGameId() != null) {
            $game = $this->entityManager->getRepository(Game::class)->find($data->getGameId());
        } else {
            $game = new Game();
            $game->setBelligerant1(new Belligerant());
            $game->setBelligerant2(new Belligerant());
            $game->getBelligerant1()->setCompo(new Compo());
            $game->getBelligerant2()->setCompo(new Compo());
        }
        
        if ($data->getScoreJoueur1() > $data->getScoreJoueur2()) {
            $vainqueur = $this->entityManager->getRepository(Joueur::class)->find($data->getJoueur1());
        } else if ($data->getScoreJoueur2() > $data->getScoreJoueur1()) {
            $vainqueur = $this->entityManager->getRepository(Joueur::class)->find($data->getJoueur2());
        } else {
            $vainqueur = null;
        }
        $game->setVainqueur($vainqueur);

        /**
         * @var $game Game
         */
        $game->setRixe($data->isRixe());
        $game->getBelligerant1()->setJoueur($this->entityManager->getRepository(Joueur::class)->find($data->getJoueur1()));
        $game->getBelligerant1()->setScore($data->getScoreJoueur1());
        if ($vainqueur != null && $vainqueur->getId() == $game->getBelligerant1()->getJoueur()->getId()) {
            $game->getBelligerant1()->setVainqueur(true);
        } else if ($vainqueur != null) {
            $game->getBelligerant2()->setVainqueur(false);
        } else {
            $game->getBelligerant2()->setVainqueur(null);
        }

        if ($data->isNoStats()) {
            $game->setNoStats(true);
            $game->getBelligerant1()->getCompo()->setNostats(true);
            $game->getBelligerant2()->getCompo()->setNostats(true);
        } else {
            $game->setNoStats(false);
            $game->getBelligerant1()->getCompo()->setNostats(false);
            $game->getBelligerant2()->getCompo()->setNostats(false);
        }

        $game->setNoRanking($data->isNoRanking() == null ? false : $data->isNoRanking());

        $game->getBelligerant1()->getCompo()->setGuilde($this->entityManager->getRepository(Guilde::class)->find($data->getGuilde1()));
        $game->getBelligerant1()->getCompo()->addPersonnage($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage1Joueur1()));
        $game->getBelligerant1()->getCompo()->addPersonnage($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage2Joueur1()));
        $game->getBelligerant1()->getCompo()->addPersonnage($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage3Joueur1()));
        $game->getBelligerant1()->getCompo()->addPersonnage($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage4Joueur1()));
        if ($data->getPersonnage5Joueur1() != null) {
            $game->getBelligerant1()->getCompo()->addPersonnage($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage5Joueur1()));
        }
        $arrayCompo1 = $game->getBelligerant1()->getCompo()->getPersonnages()->toArray();
        usort($arrayCompo1, fn($a,$b) => strcmp($a, $b));
        $game->getBelligerant1()->getCompo()->setCode(strtoupper($game->getBelligerant1()->getCompo()->getGuilde()->getCode().'_'.implode('-', $arrayCompo1)));

        $game->getBelligerant2()->setJoueur($this->entityManager->getRepository(Joueur::class)->find($data->getJoueur2()));
        $game->getBelligerant2()->setScore($data->getScoreJoueur2());
        if ($vainqueur != null && $vainqueur->getId() == $game->getBelligerant2()->getJoueur()->getId()) {
            $game->getBelligerant1()->setVainqueur(true);
        } else if ($vainqueur != null) {
            $game->getBelligerant1()->setVainqueur(false);
        } else {
            $game->getBelligerant1()->setVainqueur(null);
        }
        $game->getBelligerant2()->getCompo()->setGuilde($this->entityManager->getRepository(Guilde::class)->find($data->getGuilde2()));
        $game->getBelligerant2()->getCompo()->addPersonnage($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage1Joueur2()));
        $game->getBelligerant2()->getCompo()->addPersonnage($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage2Joueur2()));
        $game->getBelligerant2()->getCompo()->addPersonnage($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage3Joueur2()));
        $game->getBelligerant2()->getCompo()->addPersonnage($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage4Joueur2()));
        if ($data->getPersonnage5Joueur2() != null) {
            $game->getBelligerant2()->getCompo()->addPersonnage($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage5Joueur2()));
        }
        $arrayCompo2 = $game->getBelligerant2()->getCompo()->getPersonnages()->toArray();
        usort($arrayCompo2, fn($a, $b) => strcmp($a, $b));
        $game->getBelligerant2()->getCompo()->setCode(strtoupper($game->getBelligerant2()->getCompo()->getGuilde()->getCode().'_'.implode('-', $arrayCompo2)));

        $game->setMissionCombat($this->entityManager->getRepository(MissionCombat::class)->find($data->getMissionCombat()));
        $game->setMissionControle($this->entityManager->getRepository(MissionControle::class)->find($data->getMissionControle()));
        if ($data->getTournoi() != null) {
            $game->setTournoi($this->entityManager->getRepository(Tournoi::class)->find($data->getTournoi()));
        }
        $game->setRonde($data->getRonde());
        //Ajout d'heure pour gérer plus tard un éventuel classement elo et avoir l'ordre des games dans un tournoi directement bon
        
        if ($game->getRonde() != null) {
            $date = $data->getDate();
            $date->add(new DateInterval('PT'.$game->getRonde().'H'));
            $game->setDate($date);
        }   
        else{
            $game->setDate($data->getDate());
        }

        $this->entityManager->persist($game);
        $this->entityManager->flush();

        //C'est un tournoi et il y a un vainqueur
        if (!$data->isNoRanking() && $data->getGameId() == null && $data->getTournoi() != null) {
            $this->eloManager->processMatch($game);
        }   

        
        
        return $game;
    }
    
}