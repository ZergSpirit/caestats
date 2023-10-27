<?php

namespace App\Repository;

use App\Entity\Joueur;
use App\Entity\Rank;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rank>
 *
 * @method Rank|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rank|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rank[]    findAll()
 * @method Rank[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RankRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rank::class);
    }

    public function findAllActiveRanks(Joueur $joueur){
        return $this->createQueryBuilder('r')
                    ->join("r.joueur", "j")
                    ->join("r.tournoi", "t")
                    ->andWhere("j.id =:joueur")
                    ->setParameter("joueur",$joueur->getId())
                    ->andWhere("t.zitsCote is not null")
                    ->addOrderBy("t.date","desc")
                    ->getQuery()
                    ->getResult()
       ;
    }

    public function findAllArchivedRanks(Joueur $joueur){
        return $this->createQueryBuilder('r')
                    ->join("r.joueur", "j")
                    ->join("r.tournoi", "t")
                    ->andWhere("j.id =:joueur")
                    ->setParameter("joueur",$joueur->getId())
                    ->andWhere("t.zitsCote is null")
                    ->addOrderBy("t.date","desc")
                    ->getQuery()
                    ->getResult()
       ;
    }

    public function resetAllZits(){
        $this->getEntityManager()->createQuery("update App\Entity\Rank r set r.ratio=null")->execute();
    }

    public function save(Rank $rank)
    {
        $this->getEntityManager()->persist($rank);
        $this->getEntityManager()->flush($rank);
    }

    public function findTournamentNamesForPosition(Joueur $joueur, int $position)
    {
        return $this->createQueryBuilder('r')
                    ->select("t.nom")
                    ->join("r.joueur", "j")
                    ->join("r.tournoi", "t")
                    ->andWhere("j.id =:joueur")
                    ->setParameter("joueur",$joueur->getId())
                    ->andWhere("r.position =:position")
                    ->setParameter("position",$position)
                    ->getQuery()
                    ->getResult()
       ;

    }


//    /**
//     * @return Rank[] Returns an array of Rank objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Rank
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
