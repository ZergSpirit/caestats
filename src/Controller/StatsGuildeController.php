<?php

namespace App\Controller;

use App\Entity\Belligerant;
use App\Entity\Game;
use App\Entity\Guilde;
use App\Entity\MissionCombat;
use App\Entity\MissionControle;
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
        $totalBelligerants = $this->entityManager->getRepository(Belligerant::class)->countAll();
        $missionCombats = $this->entityManager->getRepository(MissionCombat::class)->findAll();
        $missionControles = $this->entityManager->getRepository(MissionControle::class)->findAll();
        
        $versusGuilde = [];
        $versusMissionsControle =[];
        $versusMissionsCombat = [];

        foreach ($guildes as $guilde) {
            foreach ($guildes as $foe) {
                $result = $this->entityManager->getRepository(Game::class)->countGamesAgainstGuilde($guilde, $foe);
                $versusGuilde[$guilde->getCode()][$foe->getCode()]['wins'] = $result['wins'];
                $versusGuilde[$guilde->getCode()][$foe->getCode()]['total'] = $result['total'];

                foreach ($missionCombats as $mission){
                    $result = $this->entityManager->getRepository(Game::class)->countGamesWithMissionCombat($guilde,$mission);
                    $versusMissionsCombat[$guilde->getCode()][$mission->getCode()]['wins'] = $result['wins'];
                    $versusMissionsCombat[$guilde->getCode()][$mission->getCode()]['total'] = $result['total'];
                }

                foreach ($missionControles as $mission){
                    $result = $this->entityManager->getRepository(Game::class)->countGamesWithMissionControle($guilde,$mission);
                    $versusMissionsControle[$guilde->getCode()][$mission->getCode()]['wins'] = $result['wins'];
                    $versusMissionsControle[$guilde->getCode()][$mission->getCode()]['total'] = $result['total'];
                }

            }
        }

        return $this->render('stats_guilde/index.html.twig', [
            'controller_name' => 'StatsGuildeController',
            'guildes' => $guildes,
            'countGuilde' => $countGuilde,
            'totalGames' => $totalGames,
            'versusGuilde' => $versusGuilde,
            'totalBelligerants' => $totalBelligerants,
            'missionCombats' => $missionCombats,
            'missionControles' => $missionControles,
            'versusMissionsCombat' => $versusMissionsCombat,
            'versusMissionsControle' => $versusMissionsControle
        ]);
    }
}
