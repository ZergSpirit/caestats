<?php

namespace App\Repository;

use App\Entity\EloLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Schema\Table;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EloLog>
 *
 * @method EloLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method EloLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method EloLog[]    findAll()
 * @method EloLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EloLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EloLog::class);
    }


    public function reset(){
        $sql = 'truncate elo_log';
        $this->getEntityManager()->getConnection()->prepare($sql)->executeQuery();
    }

    public function save(EloLog $eloLog){
        $this->getEntityManager()->persist($eloLog);
        $this->getEntityManager()->flush();
    }


//    /**
//     * @return EloLog[] Returns an array of EloLog objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EloLog
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
