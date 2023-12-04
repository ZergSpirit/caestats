<?php

namespace App\Service;

use App\Entity\Belligerant;
use App\Entity\Game;
use App\Entity\Joueur;
use App\Entity\MissionCombat;
use App\Entity\MissionControle;
use App\Entity\Rank;
use App\Entity\Tournoi;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class TournoiManager
{   

    public function __construct(private EntityManagerInterface $entityManager){}

    public function createRound(Tournoi $tournoi, MissionControle $missionControle, MissionCombat $missionCombat, bool $rixe, array $belligerants, bool $useSeeds)
    {
        $rondes = $this->entityManager->getRepository(Tournoi::class)->getRondes($tournoi);
        $ronde = 0;
        foreach ($rondes as $r){
            if($r > $ronde){
                $ronde = $r;
            }
        }

        if($ronde == 0){
            usort($belligerants, fn($a, $b) => ($b->joueur->getZits() == null? 0 : $b->joueur->getZits())-($a->joueur->getZits() == null? 0 : $a->joueur->getZits()));

            $i = 1;
            foreach($belligerants as $belligerant){

                $rank = new Rank();
                $rank->setTournoi($tournoi);
                $rank->setJoueur($belligerant->joueur);
                $rank->setPosition($i);
                $tournoi->addRank($rank);
                $i++;
            }
            
            $tournoi->setUseSeeds($useSeeds);
            $this->entityManager->getRepository(Tournoi::class)->save($tournoi);

            //Si le nombre de joueur est impaire, il faut sortir un joueur. On choisit aléatoirement parmis le/les joueur(s) avec le moins de Zits
            if((count($belligerants)) % 2 > 0){
                $arrayLast = [];
                $lastZits = $belligerants[count($belligerants)-1]->joueur->getZits();
                $idx = 0;
                //Détection du/des joueur(s) avec le moins de ZITS
                for($i = count($belligerants)-1;$i>-1;$i--){
                    $currentZits = $belligerants[$i]->joueur->getZits() == null? 0 : $belligerants[$i]->joueur->getZits();
                    if($currentZits == $lastZits){
                        $arrayLast[$idx] = $belligerants[$i];
                        $idx++;
                    }
                    else{
                        break;
                    }
                }
                $byeJoueur = null;
                if(count($arrayLast) == 1){
                    $byeJoueur = $arrayLast[0];
                }   
                else{
                    $rand = rand(0,count($arrayLast)-1);
                    $byeJoueur = $arrayLast[$rand];
                }
                $arrayJoueurBis = [];
                $i = 0;
                foreach($belligerants as $belligerant){
                    if($belligerant->joueur->getId() != $byeJoueur->joueur->getId()){
                        $arrayJoueurBis[$i] = $belligerant;
                        $i++;
                    }
                }
                $belligerants = $arrayJoueurBis;
            }
            for($i=0;$i<=(count($belligerants)/2)-1;$i++){
                $game = new Game();
                $game->setTournoi($tournoi);
                $game->setRonde(1);
                $game->setRixe($rixe);
                $game->setMissionControle($missionControle);
                $game->setMissionCombat($missionCombat);
                $game->setDate($tournoi->getDate());
                //Joueur [0] avec [15], [1] avec [14], [2] avec [13] ...
                $game->setBelligerant1(new Belligerant($belligerants[$i]->joueur,$belligerants[$i]->guilde));
                $game->setBelligerant2(new Belligerant($belligerants[count($belligerants)-1-$i]->joueur,$belligerants[count($belligerants)-1-$i]->guilde));
                $this->entityManager->getRepository(Game::class)->save($game);
            }
        }
        else{
            $joueurs = $this->createPlayerStrenghtArray($tournoi);
            dd($joueurs);
        }
    }

    public function createNextRonde(){

    }

    public function updateRanks(Tournoi $tournoi){
        $joueursWins = $this->createPlayerStrenghtArray($tournoi);
        $joueursTournoi = $this->entityManager->getRepository(Joueur::class)->findJoueursInTournament($tournoi);
        usort($joueursTournoi, fn($a, $b) => ($joueursWins[$b->getId()]['wins'] - $joueursWins[$a->getId()]['wins']));
        $this->entityManager->getRepository(Rank::class)->deleteRanks($tournoi);
        $i = 1;
        foreach($joueursTournoi as $joueur){
            $rank = new Rank();
            $rank->setTournoi($tournoi);
            $rank->setJoueur($joueur);
            $rank->setPosition($i);
            $tournoi->addRank($rank);
            $i++;
        }
        $this->entityManager->getRepository(Tournoi::class)->save($tournoi);
    }

    private function createPlayerStrenghtArray(Tournoi $tournoi){
        $joueurs = [];
        $joueursTournoi = $this->entityManager->getRepository(Joueur::class)->findJoueursInTournament($tournoi);
        foreach($joueursTournoi as $belligerant){
            $count = 0;
            $games = $this->entityManager->getRepository(Game::class)->findAllByCriteria(array("joueur" => $belligerant, "tournoi" => $tournoi));
            $foes =  [];
            foreach($games as $game){
                if($game->getVainqueur() == null){
                    $count = $count+0.1;
                }
                else if($game->getVainqueur()->getId() == $belligerant->getId()){
                    $count = $count+1;
                }
                if($game->getBelligerant1()->getJoueur()->getId() != $belligerant->getId()){
                    array_push($foes,$game->getBelligerant1()->getJoueur()->getId());
                }
                else{
                    array_push($foes,$game->getBelligerant2()->getJoueur()->getId());
                }
            }
            $joueurs[$belligerant->getId()] = array("wins" => $count, "foes" => $foes);
        }

        return $joueurs;
    }
}