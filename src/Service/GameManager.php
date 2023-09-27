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
use Doctrine\ORM\EntityManagerInterface;

class GameManager
{

    /**
     * Construct avec injection
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
        
    }


    /**
     * @param $data un resultatDTO
     * @return la $game créée
     */
    public function saveOrUpdate(ResultatDTO $data)
    {
        
        $game = new Game();
        $game->setRixe($data->isRixe());
        $belligerant1 = new Belligerant();
        $belligerant1->setJoueur($this->entityManager->getRepository(Joueur::class)->find($data->getJoueur1()));
        $belligerant1->setScore($data->getScoreJoueur1());
        if ($data->isVainqueur1()) {
            $belligerant1->setVainqueur(true);
        }
        $compo1 = new Compo();
        $compo1->setGuilde($this->entityManager->getRepository(Guilde::class)->find($data->getGuilde1()));
        $compo1->getPersonnages()->add($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage1Joueur1()));
        $compo1->getPersonnages()->add($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage2Joueur1()));
        $compo1->getPersonnages()->add($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage3Joueur1()));
        $compo1->getPersonnages()->add($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage4Joueur1()));
        $compo1->getPersonnages()->add($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage5Joueur1()));
        $arrayCompo1 = $compo1->getPersonnages()->toArray();
        usort($arrayCompo1, fn($a,$b) => strcmp($a, $b));
        $compo1->setCode(strtoupper($compo1->getGuilde()->getCode().'_'.implode('-', $arrayCompo1)));
        $belligerant1->setCompo($compo1);

        $belligerant2 = new Belligerant();
        $belligerant2->setJoueur($this->entityManager->getRepository(Joueur::class)->find($data->getJoueur2()));
        $belligerant2->setScore($data->getScoreJoueur2());
        if ($data->isVainqueur2()) {
            $belligerant2->setVainqueur(true);
        }
        $compo2 = new Compo();
        $compo2->setGuilde($this->entityManager->getRepository(Guilde::class)->find($data->getGuilde2()));
        $compo2->getPersonnages()->add($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage1Joueur2()));
        $compo2->getPersonnages()->add($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage2Joueur2()));
        $compo2->getPersonnages()->add($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage3Joueur2()));
        $compo2->getPersonnages()->add($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage4Joueur2()));
        $compo2->getPersonnages()->add($this->entityManager->getRepository(Personnage::class)->find($data->getPersonnage5Joueur2()));
        $arrayCompo2 = $compo2->getPersonnages()->toArray();
        usort($arrayCompo2, fn($a, $b) => strcmp($a, $b));
        $compo2->setCode(strtoupper($compo2->getGuilde()->getCode().'_'.implode('-', $arrayCompo1)));
        $belligerant2->setCompo($compo2);

        $game->setBelligerant1($belligerant1);
        $game->setBelligerant2($belligerant2);

        $game->setMissionCombat($this->entityManager->getRepository(MissionCombat::class)->find($data->getMissionCombat()));
        $game->setMissionControle($this->entityManager->getRepository(MissionControle::class)->find($data->getMissionControle()));
        $game->setDate($data->getDate());

        $this->entityManager->persist($game);
        
        $this->entityManager->flush();
        
        return $game;
    }
    
}