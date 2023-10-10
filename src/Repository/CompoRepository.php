<?php

namespace App\Repository;

use App\Entity\Compo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Compo>
 *
 * @method Compo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Compo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Compo[]    findAll()
 * @method Compo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Compo::class);
    }

    public function countGroupByCode()
    {
        return  $this->createQueryBuilder('c')
                   ->select('count(c.id) as count, c.code')
                   ->groupBy('c.code')
                   ->orderBy('count(c.id)','desc')
                   ->getQuery()->getArrayResult();
               ;
    }

    public function countAll()
    {
        return  $this->createQueryBuilder('c')
                   ->select('count(c.id) as count')
                   ->getQuery()->getSingleScalarResult();
               ;
    }


//    /**
//     * @return Compo[] Returns an array of Compo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Compo
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
