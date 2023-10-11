<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Joueur;
use App\Entity\Tournoi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @return Game[] Returns an array of Game objects
     */
    public function findAllByCriteria(?Joueur $joueur, ?Tournoi $tournoi){
        $query = $this->createQueryBuilder('g');
        if ($joueur != null) {
            $query->join("g.belligerant1", "b1") 
                ->join("g.belligerant2", "b2")
                ->join("b1.joueur", "j1")
                ->join("b2.joueur", "j2")
                ->andWhere('j1.id =:joueur or j2.id =:joueur')
                ->setParameter('joueur', $joueur->getId());
        }
        if ($tournoi != null) {
            $query->join("g.tournoi", "t") 
                ->andWhere('t.id =:tournoi')
                ->setParameter('tournoi', $tournoi->getId());
        }
        $query->addOrderBy('g.date', 'desc')
            ->addOrderBy('g.ronde', 'desc');
        return $query->getQuery()->getResult();
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

    public function findAllWhereTournoiIsNotNull()
    {
        return  $this->createQueryBuilder('g')
                   ->where('g.tournoi IS NOT NULL')
                   ->orderBy('g.date')
                   ->getQuery()->getResult();
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
