<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\Rank;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ZitsDetailController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager){}

    #[Route('/zits/detail/{joueurId}', name: 'app_zits_detail')]
    public function index(?int $joueurId): Response
    {

        $joueur = $this->entityManager->getRepository(Joueur::class)->find($joueurId);
        $ranks = $this->entityManager->getRepository(Rank::class)->findAllActiveRanks($joueur);
        $archiveRanks = $this->entityManager->getRepository(Rank::class)->findAllArchivedRanks($joueur);


        return $this->render('zits_detail/index.html.twig', [
            'controller_name' => 'ZitsDetailController',
            'joueur' => $joueur,
            'ranks' => $ranks,
            'archivedRanks' => $archiveRanks
        ]);
    }
}
