<?php

namespace App\Repository;

use App\Entity\Belligerant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Belligerant>
 *
 * @method Belligerant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Belligerant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Belligerant[]    findAll()
 * @method Belligerant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BelligerantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Belligerant::class);
    }

    public function countAll(){
        return $this->createQueryBuilder('b')
                ->select('count(b.id)')
                ->getQuery()->getSingleScalarResult();
    }

    public function countWinnerHavingGuilde($guilde){

        return $this->createQueryBuilder('b')
                ->select('count(b.id)')
                ->innerJoin('b.compo', 'c')
                ->where('c.guilde = :guilde')
                ->andWhere('b.vainqueur = true')
                ->setParameter('guilde', $guilde)
                ->getQuery()->getSingleScalarResult();
    }


    public function countByGuilde(){

        return $this->createQueryBuilder('b')
                ->select('count(b.id) as count, g.code, g.nom')
                ->innerJoin('b.compo', 'c')
                ->innerJoin('c.guilde', 'g')
                ->orderBy('count(b.id)','desc')
                ->groupBy('g')
                ->getQuery()->getArrayResult();
    }

    public function countHavingGuilde($guilde){

        return $this->createQueryBuilder('b')
                ->select('count(b.id)')
                ->innerJoin('b.compo', 'c')
                ->where('c.guilde = :guilde')
                ->setParameter('guilde', $guilde)
                ->getQuery()->getSingleScalarResult();
    }

//    /**
//     * @return Belligerant[] Returns an array of Belligerant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Belligerant
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
