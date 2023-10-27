<?php
namespace App\Service;

use App\Entity\Joueur;
use App\Entity\Rank;
use App\Entity\Tournoi;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ZitsManager
{ 

    public function __construct(private EntityManagerInterface $entityManager){}
    public $MIN_RATIO_FOR_ZITS_AVERAGE = 0.5;
    public $FACTOR = 20;
    public $NB_JOUEUR_FACTOR = 10;

    public function recalculateAllZits()
    {
        $this->entityManager->getRepository(Tournoi::class)->resetAllZits();
        $this->entityManager->getRepository(Rank::class)->resetAllZits();
        $this->entityManager->getRepository(Joueur::class)->resetAllZits();

        foreach ($this->entityManager->getRepository(Tournoi::class)->findBy(array(), array("date" => "asc")) as $tournoi) {
            $this->createZits($tournoi);
        }
    }

    public function createZits(Tournoi $tournoi){
        if ($tournoi->getRanks() == null || $tournoi->getRanks()->count() == 0) {
            return;
        }
        $currentDateTime = new DateTime;
        $totalZits = 0;
        $totalJoueurs = 0;
        foreach ($tournoi->getRanks() as $rank) {
            $totalZits += $rank->getJoueur()->getZits();
            $totalJoueurs++;
        }
        $avg = $this->entityManager->getRepository(Joueur::class)->avgZits();
        $tournoi->setAvgZitsAtDate(round($avg));
        $tournoi->setTotalPlayerZitsAtDate($totalZits);
        $tournoi->setNbParticipants($totalJoueurs);
        $tournoi->setZitsCote($this->createCote($tournoi->getDate(),$totalZits,$totalJoueurs, $tournoi->getAvgZitsAtDate()));
        $this->entityManager->getRepository(Tournoi::class)->save($tournoi);

        //<<Cote tournoi>>*(((nbjoueur+1-classement)/nbjoueur)+  (si rang 1,2,3 =>(0.5*(1/classement))))
        foreach ($tournoi->getRanks() as $rank) {
            $ratio = (($totalJoueurs+1-$rank->getPosition())/$totalJoueurs);
            //Bonus du top 3
            if($rank->getPosition() < 4){
                $ratio = $ratio +(0.4*(1/$rank->getPosition()));
            }
            else{
                $ratio = $ratio * (4/$rank->getPosition());
            }
            $rank->setRatio($ratio);
            $this->entityManager->getRepository(Rank::class)->save($rank);
            $joueur = $rank->getJoueur();
            if($joueur->getZits() == null){
                $joueur->setZits(0);
            }
            $zitsEarned = round($rank->getRatio() * $tournoi->getZitsCote());
            if($zitsEarned < 1){
                $zitsEarned = 1;
            }
            $joueur->setZits($joueur->getZits()+$zitsEarned);
            $this->entityManager->getRepository(Joueur::class)->save($joueur);
        }
       
    }

    public function fadeAllZits(){
        $this->entityManager->getRepository(Joueur::class)->resetAllZits();
        foreach ($this->entityManager->getRepository(Tournoi::class)->findBy(array(), array("date" => "asc")) as $tournoi) {
            $this->fadeZits($tournoi);
            foreach ($tournoi->getRanks() as $rank){
                $joueur = $rank->getJoueur();
                if($joueur->getZits() == null){
                    $joueur->setZits(0);
                }
                $joueur->setZits($joueur->getZits()+(round($rank->getRatio() * $tournoi->getZitsCote())));
                $this->entityManager->getRepository(Joueur::class)->save($joueur);
            }
        }
    }

    public function fadeZits(Tournoi $tournoi)
    {
        $tournoi->setZitsCote($this->applyTimeOnZits($tournoi->getDate(),$tournoi->getZitsCote()));
    }

     //"(((%moyenne ZITS tournoi%/%%moyenne zits nationale%%)*(2+(nbjoueur/16)))*20)*((12+1-%%anciennete_tournoi%%)/12)"
    private function createCote(DateTime $dateTournoi, $totalZits, $totalJoueurs, $avgZits)
    {
        $currentDateTime = new DateTime;
        $dateInterval = $currentDateTime->diff($dateTournoi);
        $totalMonths = 12 * $dateInterval->y + $dateInterval->m;
        //First tournoi ever
        if($avgZits == 0 && $totalZits == 0) {
            $cote = 1;
        //Cote minimal sur les ratio Zits : $MIN_RATIO_FOR_ZITS_AVERAGE
        } else if ($totalZits == 0 || $avgZits == 0) {
            $cote = $this->MIN_RATIO_FOR_ZITS_AVERAGE;
        } else {
            $cote = ($totalZits/$totalJoueurs)/$avgZits < $this->MIN_RATIO_FOR_ZITS_AVERAGE ? $this->MIN_RATIO_FOR_ZITS_AVERAGE : ($totalZits/$totalJoueurs)/$avgZits;
        }
        $cote = $cote*(2+($totalJoueurs/$this->NB_JOUEUR_FACTOR)+$this->FACTOR);
        $cote = $this->applyTimeOnZits($dateTournoi, $cote);
        return $cote;
    }

    private function applyTimeOnZits(DateTime $dateTournoi, $cote)
    {
        $currentDateTime = new DateTime;
        $dateInterval = $currentDateTime->diff($dateTournoi);
        $totalMonths = 12 * $dateInterval->y + $dateInterval->m;
        $cote = $cote*((12+1-$totalMonths)/12);
        return round($cote);
    }

}