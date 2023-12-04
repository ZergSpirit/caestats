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
    public $NEW_TOURNOI_ZITS_RATIO = 0.8;

    public function recalculateAllZits()
    {
        $this->entityManager->getRepository(Tournoi::class)->resetAllZits();
        $this->entityManager->getRepository(Rank::class)->resetAllZits();
        $this->entityManager->getRepository(Joueur::class)->resetAllZits();
        foreach ($this->entityManager->getRepository(Tournoi::class)->findBy(array("notRanked" => false), array("date" => "asc")) as $tournoi) {
            $this->createZits($tournoi, true);
        }
        $this->fadeAllZits(new DateTime());
    }

    public function createZits(Tournoi $tournoi, bool $fadePreviousTournois){
        if ($tournoi->getRanks() == null || $tournoi->getRanks()->count() == 0 || $tournoi->isNotRanked() || !$tournoi->isFinished()) {
            $tournoi->setZitsCote(null);
            $this->entityManager->getRepository(Tournoi::class)->save($tournoi);
            return;
        }
        //On fade tous les autres tournois à date de celui-ci. En effet, la cote du tournoi hors Fade ne doit jamais bouger dans le temps.
        //C'est les points des joueurs qui se réduisent dans le temps par le fade. Donc, au jour J ou le tournoi est fini, on calcul sa cote par rapport
        //aux classements des joueurs à cette date, et ca ne bougera plus.
        if($fadePreviousTournois) {
            $this->fadeAllZits($tournoi->getDate());
        }
        $totalZits = 0;
        $totalJoueurs = 0;
        foreach ($tournoi->getRanks() as $rank) {
            $totalZits += $rank->getJoueur()->getZits();
            $totalJoueurs++;
        }
        $avg = $this->entityManager->getRepository(Joueur::class)->avgZits();
        $this->createCote($totalZits,$totalJoueurs, $avg, $tournoi);
        $this->entityManager->getRepository(Tournoi::class)->save($tournoi);

        //<<Cote tournoi>>*(((nbjoueur+1-classement)/nbjoueur)+  (si rang 1,2,3 =>(0.5*(1/classement))))
        foreach ($tournoi->getRanks() as $rank) {
            $ratio = (($totalJoueurs+1-$rank->getPosition())/$totalJoueurs);
            //$ratio = 1;
            //Bonus du top 3
            if($rank->getPosition() < 4){
                $ratio = $ratio +(0.6*(1/$rank->getPosition()));
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

    public function fadeAllZits(?DateTime $date){
        //$this->entityManager->getRepository(Joueur::class)->resetAllZits();
        //TODO, sur la méthode ci dessus, l'entiyManager n'a pas l'air de flush les changements
        foreach ($this->entityManager->getRepository(Joueur::class)->findAll() as $joueur) {
            $joueur->setZits(null);
            $this->entityManager->getRepository(Joueur::class)->save($joueur);
        }
        foreach ($this->entityManager->getRepository(Tournoi::class)->findBy(array(), array("date" => "asc")) as $tournoi) {
            //Pas de fade sur des tournoi qui n'ont pas encore de cote
            if($tournoi->getZitsCote() == null){
                continue;
            }
            $fade = $this->calculateFade($tournoi->getDate(), $date, $tournoi);
            $this->entityManager->getRepository(Tournoi::class)->save($tournoi);
            foreach ($tournoi->getRanks() as $rank){
                $joueur = $this->entityManager->getRepository(Joueur::class)->find($rank->getJoueur()->getId());
                if($joueur->getZits() == null){
                    $joueur->setZits(0);
                }
                $rank->setZitsEarned(round($rank->getRatio() * $tournoi->getZitsCote()*$fade));
                $joueur->setZits($joueur->getZits()+$rank->getZitsEarned());
                $this->entityManager->getRepository(Joueur::class)->save($joueur);
                $this->entityManager->getRepository(Rank::class)->save($rank);
            }
        }
    }

     //"(((%moyenne ZITS tournoi%/%%moyenne zits nationale%%)*(1+(nbjoueur/16)))*20)*((12+1-%%anciennete_tournoi%%)/12)"
    private function createCote($totalZits, $totalJoueurs, $avgZits, Tournoi $tournoi)
    {
        $tournoi->setAvgZitsAtDate(round($avgZits));
        $tournoi->setTotalPlayerZitsAtDate($totalZits);
        $tournoi->setNbParticipants($totalJoueurs);
        //First tournoi ever
        if($avgZits == 0 && $totalZits == 0) {
            $cote = $this->NEW_TOURNOI_ZITS_RATIO;
        //Cote minimal sur les ratio Zits : $MIN_RATIO_FOR_ZITS_AVERAGE
        } else if ($totalZits == 0 || $avgZits == 0) {
            $cote = $this->MIN_RATIO_FOR_ZITS_AVERAGE;
        } else {
            $cote = ($totalZits/$totalJoueurs)/$avgZits < $this->MIN_RATIO_FOR_ZITS_AVERAGE ? $this->MIN_RATIO_FOR_ZITS_AVERAGE : ($totalZits/$totalJoueurs)/$avgZits;
        }
        //$cote = $cote*(2+($totalJoueurs/$this->NB_JOUEUR_FACTOR)+$this->FACTOR);
        $cote = $cote*(1+($totalJoueurs/$this->NB_JOUEUR_FACTOR))*$this->FACTOR;
        $tournoi->setZitsCote($cote);
        $this->entityManager->getRepository(Tournoi::class)->save($tournoi);
        return $cote;
    }

    private function calculateFade(DateTime $dateTournoi, ?DateTime $currentDateTime, Tournoi $tournoi)
    {
        if($currentDateTime == null){
            $currentDateTime = new DateTime;
        }
        $dateInterval = $currentDateTime->diff($dateTournoi);
        $totalMonths = 12 * $dateInterval->y + $dateInterval->m;
        $tournoi->setZitsFadingMonthElapsed($totalMonths);
        return ((12-$totalMonths)/12);
    }

}