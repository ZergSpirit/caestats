<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Joueur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        
    }

    #[Route('/games/{joueurId}', name: 'app_game')]
    public function index(?int $joueurId = null): Response
    {
        $joueur = null;
        if ($joueurId != null) {
            $joueur = $this->entityManager->getRepository(Joueur::class)->find($joueurId);
            $games = $this->entityManager->getRepository(Game::class)->findAllByJoueurOrderBydate($joueur);
        } else {
            $games = $this->entityManager->getRepository(Game::class)->findAllByJoueurOrderBydate(null);
        }

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'games' => $games,
            'joueur' => $joueur
        ]);
    }
}
