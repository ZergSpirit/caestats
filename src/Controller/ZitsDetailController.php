<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\Rank;
use App\Entity\Tournoi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ZitsDetailController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager){}

    #[Route('/zits/detail/joueur/{joueurId}', name: 'app_zits_joueur_detail')]
    public function joueurDetail(?int $joueurId): Response
    {

        $joueur = $this->entityManager->getRepository(Joueur::class)->find($joueurId);
        $ranks = $this->entityManager->getRepository(Rank::class)->findAllActiveRanks($joueur);
        $archiveRanks = $this->entityManager->getRepository(Rank::class)->findAllArchivedRanks($joueur);


        return $this->render('zits_detail/joueur.html.twig', [
            'controller_name' => 'ZitsDetailController',
            'joueur' => $joueur,
            'ranks' => $ranks,
            'archivedRanks' => $archiveRanks
        ]);
    }

    #[Route('/zits/detail/tournoi/{tournoiId}', name: 'app_zits_tournoi_detail')]
    public function tournoiDetail(?int $tournoiId): Response
    {
        $tournoi = $this->entityManager->getRepository(Tournoi::class)->find($tournoiId);
        return $this->render('zits_detail/tournoi.html.twig', [
            'controller_name' => 'ZitsDetailController',
            'tournoi' => $tournoi
        ]);
    }
}
