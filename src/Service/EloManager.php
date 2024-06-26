<?php

namespace App\Service;

use App\Entity\EloLog;
use App\Entity\Game;
use App\Entity\Joueur;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Zelenin\Elo\Player;

class EloManager
{   
    public $STARTING_ELO = 1000;
    public $K_FACTOR = 32;

    /**
     * Construct avec injection
     */
    public function __construct(private EntityManagerInterface $entityManager, private LoggerInterface $logger)
    {
        
    }

    public function processMatch(Game $game)
    {   
        $this->logger->info("processMatch game: ".$game->getId());
        if ($game->getTournoi() !== null) {
            if($game->getBelligerant1()->getScore() === $game->getBelligerant2()->getScore()){
                $joueurWinner = null;
            }
            else {
                $joueurWinner = $game->getBelligerant1()->getScore() > $game->getBelligerant2()->getScore() ? $game->getBelligerant1()->getJoueur() : $game->getBelligerant2()->getJoueur();
            }
            $joueur1 = $game->getBelligerant1()->getJoueur();
            $joueur2 = $game->getBelligerant2()->getJoueur();
        } 
        else {
            $this->logger->info("processMatch fini, pas de tournoi ");
            return;
        }

        if($game->getBelligerant1()->getScore() === null || $game->getBelligerant2()->getScore() === null ){
            $this->logger->info("processMatch fini, score manquant. Score 1 : ".$game->getBelligerant1()->getScore()."score 2 :".$game->getBelligerant2()->getScore());
            return;
        }

        if ($joueur1->getElo() === null) {
            $joueur1->setElo($this->STARTING_ELO);
        }
        if ($joueur2->getElo() === null) {
            $joueur2->setElo($this->STARTING_ELO);
        }

        $player1 = new Player($joueur1->getElo());
        $player2 = new Player($joueur2->getElo());
        $match = new EloMatch($player1, $player2);
        if ($joueurWinner === null) {
            $match->setScore(0.5, 0.5);
        }
        else if ($joueurWinner->getId() === $joueur1->getId()) {
            $match->setScore(1, 0);
        } 
        else {
            $match->setScore(0, 1);
        }  
        $match->setK($this->K_FACTOR);
        $match->count();

        $joueur1PreviousElo = $joueur1->getElo();
        $joueur2PreviousElo = $joueur2->getElo();

        $joueur1->setElo($player1->getRating());
        $joueur2->setElo($player2->getRating());
        $this->entityManager->getRepository(Joueur::class)->save($joueur1);
        $this->entityManager->getRepository(Joueur::class)->save($joueur2);

        $eloLog = new EloLog();
        $eloLog->setGame($game);
        $eloLog->setPreviousEloJoueur1($joueur1PreviousElo);
        $eloLog->setPreviousEloJoueur2($joueur2PreviousElo);
        $eloLog->setVariationEloJoueur1($joueur1->getElo() - $joueur1PreviousElo);
        $eloLog->setVariationEloJoueur2($joueur2->getElo() - $joueur2PreviousElo);

        $this->entityManager->getRepository(EloLog::class)->save($eloLog);
    }

    public function updateAllJoueurs()
    {   
        $this->entityManager->getRepository(Joueur::class)->resetAllElo();
        $this->entityManager->getRepository(EloLog::class)->reset();
        $games = $this->entityManager->getRepository(Game::class)->findAllWhereTournoiIsNotNullAndRanked();
        foreach ($games as $game) {
            $this->processMatch($game);
        }
    }
}