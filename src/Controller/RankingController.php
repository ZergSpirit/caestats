<?php

namespace App\Controller;

use App\Entity\Belligerant;
use App\Entity\Game;
use App\Entity\Joueur;
use App\Entity\Rank;
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
        $joueurs = $this->entityManager->getRepository(Joueur::class)->findAll();
        $joueurRanked = $this->entityManager->getRepository(Joueur::class)->findAllSortedByEloRanking();

        $joueurGames = [];
        foreach ($joueurs as $joueur) {
            $lastGame = $this->entityManager->getRepository(Game::class)->lastGame($joueur);
            $joueurGames[$joueur->getId()] = $this->entityManager->getRepository(Game::class)->countGames($joueur);
            $joueurGames[$joueur->getId()]['lastGame'] = $lastGame == null? null : $lastGame->getDate();
            $joueurGames[$joueur->getId()]['ties'] = $this->entityManager->getRepository(Game::class)->countTies($joueur);
            $joueurGames[$joueur->getId()][1] = $this->entityManager->getRepository(Rank::class)->findTournamentNamesForPosition($joueur,1);
            $joueurGames[$joueur->getId()][2] = $this->entityManager->getRepository(Rank::class)->findTournamentNamesForPosition($joueur,2);
            $joueurGames[$joueur->getId()][3] = $this->entityManager->getRepository(Rank::class)->findTournamentNamesForPosition($joueur,3);
        }
        $joueursRankedZits = $this->entityManager->getRepository(Joueur::class)->findAllSortedByZitsRanking();


        return $this->render('ranking/index.html.twig', [
            'controller_name' => 'RankingController',
            'joueursRanked' => $joueurRanked,
            'joueurGames' => $joueurGames,
            'joueursRankedZits' => $joueursRankedZits
        ]);
    }
}
