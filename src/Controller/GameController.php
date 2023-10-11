<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Guilde;
use App\Entity\Joueur;
use App\Entity\Tournoi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        
    }

    #[Route('/games', name: 'app_game')]
    public function index(#[MapQueryParameter] ?int $joueurId = null, #[MapQueryParameter] ?int $tournoiId = null, #[MapQueryParameter] ?int $guildeId = null, #[MapQueryParameter] ?string $compoCode = null): Response
    {
        $joueur = null;
        if ($joueurId != null) {
            $joueur = $this->entityManager->getRepository(Joueur::class)->find($joueurId);
        } 
        $tournoi = null;
        if ($tournoiId != null) {
            $tournoi = $this->entityManager->getRepository(Tournoi::class)->find($tournoiId);
        } 
        $guilde = null;
        if ($guildeId != null) {
            $guilde = $this->entityManager->getRepository(Guilde::class)->find($guildeId);
        }

        $games = $this->entityManager->getRepository(Game::class)->findAllByCriteria($joueur, $tournoi, $guilde, $compoCode);

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'games' => $games,
            'joueur' => $joueur,
            'tournoi' => $tournoi
        ]);
    }
}
