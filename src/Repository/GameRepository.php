<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Guilde;
use App\Entity\Joueur;
use App\Entity\MissionCombat;
use App\Entity\MissionControle;
use App\Entity\Personnage;
use App\Entity\Tournoi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

     /**
    * @return Returns un array avec le nombre de victoire, le nombre de game et la dernière game
     */
    public function countGames(Joueur $joueur)
    {
        
        $countGames = [];

        $countGames['total'] = $this->createQueryBuilder('g')
                                ->select('count(g.id)')
                                ->innerJoin("g.belligerant1","b1")
                                ->innerJoin("g.belligerant2","b2")
                                ->innerJoin("b1.joueur", "j1")
                                ->innerJoin("b2.joueur", "j2")
                                ->innerJoin("g.tournoi", "t")
                                ->andWhere("j1.id = :joueur or j2.id =:joueur")
                                ->andWhere("t.notRanked = false")
                                ->setParameter("joueur", $joueur->getId())
                                ->getQuery()->getSingleScalarResult();

        $countGames['totalWins'] = $this->createQueryBuilder('g')
                                    ->select('count(g.id)')
                                    ->innerJoin("g.belligerant1","b1")
                                    ->innerJoin("g.belligerant2","b2")
                                    ->innerJoin("b1.joueur", "j1")
                                    ->innerJoin("b2.joueur", "j2")
                                    ->innerJoin("g.tournoi", "t")
                                    ->andWhere("j1.id = :joueur or j2.id =:joueur")
                                    ->andWhere("(b1.vainqueur = true and j1.id = :joueur) or (b2.vainqueur=true and j2.id =:joueur)")
                                    ->andWhere("t.notRanked = false")
                                    ->setParameter("joueur", $joueur->getId())
                                    ->getQuery()->getSingleScalarResult();


        return $countGames;
    }

    /**
     * @return Game[] Returns an array of Game objects
     */
    public function findAllByCriteria(?Joueur $joueur=null, ?Tournoi $tournoi=null, ?Guilde $guilde=null, ?ArrayCollection $compo = null, ?bool $rixe=null, ?MissionControle $missionControle=null, ?MissionCombat $missionCombat=null, ?Joueur $joueur2 = null, ?Guilde $guilde2= null, ?ArrayCollection $compo2= null)
    {
        $query = $this->createQueryBuilder('g');
        $query->join("g.missionControle", "controle")
            ->join("g.missionCombat", "combat")
            ->join("g.belligerant1", "b1") 
            ->join("g.belligerant2", "b2")
            ->join("b1.joueur", "j1")
            ->join("b2.joueur", "j2")
            ->join("g.tournoi", "t") 
            ->join("b1.compo", "c1")
            ->join("b2.compo", "c2")
            ->join("c1.guilde", "g1")
            ->join("c2.guilde", "g2");

        if ($rixe != null) {
            $query->andWhere('g.rixe =:rixe')
                ->setParameter('rixe', $rixe);
        }

        if ($missionControle != null) {
            $query->andWhere('controle.id =:controle')
                ->setParameter('controle', $missionControle->getId());
        }

        if ($missionCombat != null) {
            $query->andWhere('combat.id =:combat')
                ->setParameter('combat', $missionCombat->getId());
        }

        if ($tournoi != null) {
            $query->andWhere('t.id =:tournoi')
                ->setParameter('tournoi', $tournoi->getId());
        }
    
        if (($compo != null && $compo->count() > 0) && ($compo2 != null && $compo2->count() > 0)) {
            $i = 0;
            $queryCompo = "";
            $queryC1 = "1=1";
            $queryC2 = "1=1";
            foreach ($compo as $perso) {
                $queryC1=$queryC1." and c1.code like :perso".$i;
                $query->setParameter("perso".$i, "%".$perso->getCode()."%");
                $i++;
            }
            $i = 6;
            foreach ($compo2 as $perso) {
                $queryC2=$queryC2." and c2.code like :perso".$i;
                $query->setParameter("perso".$i, "%".$perso->getCode()."%");
                $i++;
            }
            $queryCompo = "((".$queryC1.") and (".$queryC2."))";
            
            $i = 0;
            $queryC1 = "1=1";
            $queryC2 = "1=1";
            foreach ($compo as $perso) {
                $queryC1=$queryC1." and c2.code like :perso".$i;
                $query->setParameter("perso".$i, "%".$perso->getCode()."%");
                $i++;
            }
            $i = 6;
            foreach ($compo2 as $perso) {
                $queryC2=$queryC2." and c1.code like :perso".$i;
                $query->setParameter("perso".$i, "%".$perso->getCode()."%");
                $i++;
            }
            $queryCompo = $queryCompo. "or ((".$queryC1.") and (".$queryC2."))";
            $query->andWhere($queryCompo);
        } else {

            if ($compo != null && $compo->count() > 0 ) {
                $i = 0;
                $queryC1 = "1=1";
                $queryC2 = "1=1";
                foreach ($compo as $perso) {
                    $queryC1=$queryC1." and c1.code like :perso".$i;
                    $queryC2=$queryC2." and c2.code like :perso".$i;
                    $query->setParameter("perso".$i, "%".$perso->getCode()."%");
                    $i++;
                }
                $query->andWhere("(".$queryC1.") or (".$queryC2.")");
                
            }
            if ($compo2 != null && $compo2->count() > 0 ) {
                $i = 6;
                $queryC1 = "1=1";
                $queryC2 = "1=1";
                foreach ($compo2 as $perso) {
                    $queryC1=$queryC1." and c1.code like :perso".$i;
                    $queryC2=$queryC2." and c2.code like :perso".$i;
                    $query->setParameter("perso".$i, "%".$perso->getCode()."%");
                    $i++;
                }
                $query->andWhere("(".$queryC1.") or (".$queryC2.")");
            }
        }

        if ($joueur != null) {
            $query->andWhere('j1.id =:joueur or j2.id =:joueur')
                ->setParameter('joueur', $joueur->getId());
        }
        if ($joueur2 != null) {
            $query->andWhere('j1.id =:joueur2 or j2.id =:joueur2')
                ->setParameter('joueur2', $joueur2->getId());
        }
       
       
        if ($guilde != null) {
            $query->andWhere('g1.id =:guilde or g2.id =:guilde')
                ->setParameter('guilde', $guilde->getId());
        }
        if ($guilde2 != null) {
            $query->andWhere('g1.id =:guilde2 or g2.id =:guilde2')
                ->setParameter('guilde2', $guilde2->getId());
        }
        $query->addOrderBy('g.date', 'desc')
            ->addOrderBy('g.ronde', 'desc');
        
        return $query->getQuery()->getResult();
    }

     /**
     * @return Array[] avec ['wins'] et ['total']
     */
    public function countGamesAgainstGuilde(Guilde $guilde, Guilde $foe)
    {   
        $result = [];
        $query = $this->createQueryBuilder('g')
            ->select("count(g.id)")
            ->join("g.belligerant1", "b1") 
            ->join("g.belligerant2", "b2")
            ->join("b1.joueur", "j1")
            ->join("b2.joueur", "j2")
            ->join("g.tournoi", "t") 
            ->join("b1.compo", "c1")
            ->join("b2.compo", "c2")
            ->join("c1.guilde", "g1")
            ->join("c2.guilde", "g2")
            ->andWhere('(g1.id =:guilde and g2.id =:foe) or (g2.id =:guilde and g1.id =:foe)')
            ->andWhere('(g1.id =:guilde and b1.vainqueur=1) or (g2.id =:guilde and b2.vainqueur=1)')
            ->setParameter('guilde', $guilde->getId())
            ->setParameter('foe', $foe->getId());
        $result['wins'] =  $query->getQuery()->getSingleScalarResult();
        $query = $this->createQueryBuilder('g')
            ->select("count(g.id)")
            ->join("g.belligerant1", "b1") 
            ->join("g.belligerant2", "b2")
            ->join("b1.joueur", "j1")
            ->join("b2.joueur", "j2")
            ->join("g.tournoi", "t") 
            ->join("b1.compo", "c1")
            ->join("b2.compo", "c2")
            ->join("c1.guilde", "g1")
            ->join("c2.guilde", "g2")
            ->andWhere('(g1.id =:guilde and g2.id =:foe) or (g2.id =:guilde and g1.id =:foe)')
            ->setParameter('guilde', $guilde->getId())
            ->setParameter('foe', $foe->getId());
        $result['total'] =  $query->getQuery()->getSingleScalarResult();
        return $result;
    }

    public function countGamesWithMissionCombat(Guilde $guilde, MissionCombat $missionCombat)
    {
        $result = [];
        $query = $this->createQueryBuilder('g')
            ->select("count(g.id)")
            ->join("g.belligerant1", "b1") 
            ->join("g.belligerant2", "b2")
            ->join("b1.joueur", "j1")
            ->join("b2.joueur", "j2")
            ->join("g.tournoi", "t") 
            ->join("b1.compo", "c1")
            ->join("b2.compo", "c2")
            ->join("c1.guilde", "g1")
            ->join("c2.guilde", "g2")
            ->join("g.missionCombat","m")
            ->andWhere('(g1.id =:guilde or g2.id =:guilde)')
            ->andWhere('m.id =:mission')
            ->andWhere('(g1.id =:guilde and b1.vainqueur=1) or (g2.id =:guilde and b2.vainqueur=1)')
            ->setParameter('guilde', $guilde->getId())
            ->setParameter("mission",$missionCombat->getId());
        $result['wins'] =  $query->getQuery()->getSingleScalarResult();
        $query = $this->createQueryBuilder('g')
            ->select("count(g.id)")
            ->join("g.belligerant1", "b1") 
            ->join("g.belligerant2", "b2")
            ->join("b1.joueur", "j1")
            ->join("b2.joueur", "j2")
            ->join("g.tournoi", "t") 
            ->join("b1.compo", "c1")
            ->join("b2.compo", "c2")
            ->join("c1.guilde", "g1")
            ->join("c2.guilde", "g2")
            ->join("g.missionCombat","m")
            ->andWhere('(g1.id =:guilde or g2.id =:guilde)')
            ->andWhere('m.id =:mission')
            ->setParameter('guilde', $guilde->getId())
            ->setParameter("mission",$missionCombat->getId());
        $result['total'] =  $query->getQuery()->getSingleScalarResult();
        return $result;
    }

    public function countGamesWithMissionControle(Guilde $guilde, MissionControle $missionControle)
    {
        $result = [];
        $query = $this->createQueryBuilder('g')
            ->select("count(g.id)")
            ->join("g.belligerant1", "b1") 
            ->join("g.belligerant2", "b2")
            ->join("b1.joueur", "j1")
            ->join("b2.joueur", "j2")
            ->join("g.tournoi", "t") 
            ->join("b1.compo", "c1")
            ->join("b2.compo", "c2")
            ->join("c1.guilde", "g1")
            ->join("c2.guilde", "g2")
            ->join("g.missionControle","m")
            ->andWhere('(g1.id =:guilde or g2.id =:guilde)')
            ->andWhere('m.id =:mission')
            ->andWhere('(g1.id =:guilde and b1.vainqueur=1) or (g2.id =:guilde and b2.vainqueur=1)')
            ->setParameter('guilde', $guilde->getId())
            ->setParameter("mission",$missionControle->getId());
        $result['wins'] =  $query->getQuery()->getSingleScalarResult();
        $query = $this->createQueryBuilder('g')
            ->select("count(g.id)")
            ->join("g.belligerant1", "b1") 
            ->join("g.belligerant2", "b2")
            ->join("b1.joueur", "j1")
            ->join("b2.joueur", "j2")
            ->join("g.tournoi", "t") 
            ->join("b1.compo", "c1")
            ->join("b2.compo", "c2")
            ->join("c1.guilde", "g1")
            ->join("c2.guilde", "g2")
            ->join("g.missionControle","m")
            ->andWhere('(g1.id =:guilde or g2.id =:guilde)')
            ->andWhere('m.id =:mission')
            ->setParameter('guilde', $guilde->getId())
            ->setParameter("mission",$missionControle->getId());
        $result['total'] =  $query->getQuery()->getSingleScalarResult();
        return $result;
    }



    public function countTies(Joueur $joueur)
    {
        return $this->createQueryBuilder('g')
                        ->select('count(g.id)')
                        ->innerJoin("g.belligerant1", "b1")
                        ->innerJoin("g.belligerant2", "b2")
                        ->innerJoin("b1.joueur", "j1")
                        ->innerJoin("b2.joueur", "j2")
                        ->andWhere('j1.id =:joueur or j2.id =:joueur')
                        ->andWhere('g.vainqueur is null')
                        ->setParameter("joueur", $joueur->getId())
                        ->orderBy("g.date", "desc")
                        ->getQuery()->getSingleScalarResult();
    }

    /**
     */
    public function lastGame(Joueur $joueur)
    {
        return $this->createQueryBuilder('g')
                                    ->innerJoin("g.belligerant1", "b1")
                                    ->innerJoin("g.belligerant2", "b2")
                                    ->innerJoin("b1.joueur", "j1")
                                    ->innerJoin("b2.joueur", "j2")
                                    ->andWhere('j1.id =:joueur or j2.id =:joueur')
                                    ->setParameter("joueur", $joueur->getId())
                                    ->orderBy("g.date", "desc")
                                    ->setMaxResults(1)
                                    ->getQuery()->getOneOrNullResult();

    }
    

    public function findAllByJoueurOrderBydate(?Joueur $joueur){
        if ($joueur == null) {
            return  $this->createQueryBuilder('g')
                ->where('g.tournoi IS NOT NULL')
                ->orderBy('g.date','desc')
                ->getQuery()->getResult();
        }
        return  $this->createQueryBuilder('g')
            ->join("g.belligerant1", "b1") 
            ->join("g.belligerant2", "b2")
            ->join("b1.joueur", "j1")
            ->join("b2.joueur", "j2")
            ->where('g.tournoi IS NOT NULL')
            ->andWhere('j1.id =:joueur or j2.id =:joueur')
            ->setParameter('joueur', $joueur->getId())
            ->orderBy('g.date','desc')
            ->getQuery()->getResult();
    }

    public function findAllWhereTournoiIsNotNullAndRanked()
    {
        return  $this->createQueryBuilder('g')
                   ->andWhere('g.tournoi IS NOT NULL')
                   ->andWhere('g.notRanked = false')
                   ->orderBy('g.date')
                   ->getQuery()->getResult();
    }

    public function countAll(){
        return  $this->createQueryBuilder('g')
                    ->select('count(g.id)')
                    ->getQuery()->getSingleScalarResult();
    }

    public function save(Game $game)
    {
        $this->getEntityManager()->persist($game);
        $this->getEntityManager()->flush($game);
    }

//    /**
//     * @return Game[] Returns an array of Game objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Game
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
