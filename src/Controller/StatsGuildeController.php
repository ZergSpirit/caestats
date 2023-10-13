<?php

namespace App\Controller;

use App\Entity\Belligerant;
use App\Entity\Game;
use App\Entity\Guilde;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatsGuildeController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager) 
    {

    }

    #[Route('/stats/guilde', name: 'app_stats_guilde')]
    public function index(): Response
    {

        $guildes = $this->entityManager->getRepository(Guilde::class)->findBy(array(),array('nom' => 'ASC'));
        $countGuilde = $this->entityManager->getRepository(Belligerant::class)->countByGuilde();
        $totalGames = $this->entityManager->getRepository(Game::class)->countAll();
        
        $versusGuilde = [];

        foreach ($guildes as $guilde) {
            foreach ($guildes as $foe) {
                $result = $this->entityManager->getRepository(Game::class)->countGamesAgainstGuilde($guilde, $foe);
                $versusGuilde[$guilde->getCode()][$foe->getCode()]['wins'] = $result['wins'];
                $versusGuilde[$guilde->getCode()][$foe->getCode()]['total'] = $result['total'];
            }
        }

        return $this->render('stats_guilde/index.html.twig', [
            'controller_name' => 'StatsGuildeController',
            'guildes' => $guildes,
            'countGuilde' => $countGuilde,
            'totalGames' => $totalGames,
            'versusGuilde' => $versusGuilde
        ]);
    }
}
