<?php

namespace App\Service;

use App\Entity\Belligerant;
use App\Entity\Game;
use App\Entity\MissionCombat;
use App\Entity\MissionControle;
use App\Entity\Tournoi;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class TournoiManager
{   

    public function __construct(private EntityManagerInterface $entityManager){}

    public function createFirstRound(Tournoi $tournoi, MissionControle $missionControle, MissionCombat $missionCombat, bool $rixe, array $belligerants, bool $useSeeds)
    {
        $games = $this->entityManager->getRepository(Game::class)->findAllByCriteria(array(
            "tournoi" => $tournoi,
            "ronde" => 1
        ));
        if(count($games) > 0){
            return;
        }

        $tournoi->setUseSeeds($useSeeds);
        $this->entityManager->getRepository(Tournoi::class)->save($tournoi);

        usort($belligerants, fn($a, $b) => ($b->joueur->getZits() == null? 0 : $b->joueur->getZits())-($a->joueur->getZits() == null? 0 : $a->joueur->getZits()));
        //Si le nombre de joueur est impaire, il faut sortir un joueur de la première ronde. On choisit aléatoirement parmis le/les joueur(s) avec le moins de Zits
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
}