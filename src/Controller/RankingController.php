<?php

namespace App\Controller;

use App\Entity\Belligerant;
use App\Entity\Game;
use App\Entity\Joueur;
use App\Service\EloManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RankingController extends AbstractController
{   
    public function __construct(private EntityManagerInterface $entityManager, private EloManager $eloMananger)
    {
        
    }

    #[Route('/ranking/regenerate', name: 'ranking_regenerate')]
    public function regenerate(): Response
    {   

        $this->eloMananger->updateAllJoueurs();

        return $this->redirect('/ranking');
    }

    #[Route('/ranking', name: 'app_ranking')]
    public function index(): Response
    {   
        $joueurRanked = $this->entityManager->getRepository(Joueur::class)->findAllSortedByEloRanking();

        $joueurGames = [];
        foreach ($joueurRanked as $joueur) {
            $lastGame = $this->entityManager->getRepository(Game::class)->lastGame($joueur);
            $joueurGames[$joueur->getId()] = $this->entityManager->getRepository(Belligerant::class)->countGames($joueur);
            $joueurGames[$joueur->getId()]['lastGame'] = $lastGame == null? null : $lastGame->getDate();
            $joueurGames[$joueur->getId()]['ties'] = $this->entityManager->getRepository(Game::class)->countTies($joueur);
        }

        return $this->render('ranking/index.html.twig', [
            'controller_name' => 'RankingController',
            'joueursRanked' => $joueurRanked,
            'joueurGames' => $joueurGames
        ]);
    }
}
