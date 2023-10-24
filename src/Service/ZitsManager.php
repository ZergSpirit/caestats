<?php
namespace App\Service;

use App\Entity\Joueur;
use App\Entity\Tournoi;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ZitsManager
{ 

    public function __construct(private EntityManagerInterface $entityManager){}

    public function updateCote(Tournoi $tournoi){
        if ($tournoi->getRanks() == null || $tournoi->getRanks()->count() == 0) {
            return;
        }
        $currentDateTime = new DateTime;
        $dateTournoi = $tournoi->getDate();
        $dateInterval = $currentDateTime->diff($dateTournoi);
        $totalMonths = 12 * $dateInterval->y + $dateInterval->m;

        $totalZits = 0;
        foreach ($tournoi->getRanks() as $rank) {
            $totalZits += $rank->getJoueur()->getZits();
        }
        $avg = $this->entityManager->getRepository(Joueur::class)->avgZits();

        //"(((%moyenne ZITS tournoi%/%%moyenne zits nationale%%)*(2+(nbjoueur/16)))*20)*((12+1-%%anciennete_tournoi%%)/12)"

        $cote = ($totalZits/$tournoi->getRanks()->count())/$avg;
        $cote = $cote*(2+($tournoi->getRanks()->count()/16)*20)*((12+1-$totalMonths)/12);

        $tournoi->setZitsCote($cote);
        $this->entityManager->getRepository(Tournoi::class)->save($tournoi);
       
    }

}