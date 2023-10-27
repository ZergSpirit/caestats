<?php

namespace App\Repository;

use App\Entity\Joueur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Joueur>
 *
 * @method Joueur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Joueur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Joueur[]    findAll()
 * @method Joueur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JoueurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Joueur::class);
    }

    public function resetAllZits(){
        $this->getEntityManager()->createQuery("update App\Entity\Joueur j set j.zits=null")->execute();
    }

    public function avgZits(){
        $this->getEntityManager()->createQuery("select AVG(j.zits) from App\Entity\Joueur j")->execute();
    }

    public function resetAllElo(){
        $this->getEntityManager()->createQuery("update App\Entity\Joueur j set j.elo=null")->execute();
    }

    public function save(Joueur $joueur)
    {
        $this->getEntityManager()->persist($joueur);
        $this->getEntityManager()->flush($joueur);
    }

    public function findAllSortedByEloRanking(){
        return $this->createQueryBuilder('j')
                   ->where('j.elo IS NOT NULL')
                   ->orderBy('j.elo','desc')
                   ->getQuery()->getResult();
    }

    public function findAllSortedByZitsRanking(){
        return $this->createQueryBuilder('j')
                   ->where('j.zits IS NOT NULL')
                   ->orderBy('j.zits','desc')
                   ->getQuery()->getResult();
    }

//    /**
//     * @return Joueur[] Returns an array of Joueur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Joueur
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
