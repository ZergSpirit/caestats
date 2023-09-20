<?php

namespace App\Repository;

use App\Entity\MissionCombat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MissionCombat>
 *
 * @method MissionCombat|null find($id, $lockMode = null, $lockVersion = null)
 * @method MissionCombat|null findOneBy(array $criteria, array $orderBy = null)
 * @method MissionCombat[]    findAll()
 * @method MissionCombat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MissionCombatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MissionCombat::class);
    }

//    /**
//     * @return MissionCombat[] Returns an array of MissionCombat objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MissionCombat
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
