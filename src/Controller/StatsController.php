<?php

namespace App\Controller;

use App\Entity\Belligerant;
use App\Entity\Compo;
use App\Entity\Guilde;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Cast\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager){}

    #[Route('/stats', name: 'app_stats')]
    public function index(): Response
    {

        $guildes = $this->entityManager->getRepository(Guilde::class)->findAll();
        $totalBelligerants = $this->entityManager->getRepository(Belligerant::class)->countAll();
        $resultWinners = [];
        $results = [];
        foreach ($guildes as $guilde) {
            $resultWinners[$guilde->getNom()] = $this->entityManager->getRepository(Belligerant::class)->countWinnerHavingGuilde($guilde);
            $results[$guilde->getNom()] = $this->entityManager->getRepository(Belligerant::class)->countHavingGuilde($guilde);
        }

        $compoCount = $this->entityManager->getRepository(Compo::class)->countGroupByCode();
        $totalCompo = $this->entityManager->getRepository(Compo::class)->countAll();
        return $this->render('stats/index.html.twig', [
            'controller_name' => 'StatsController',
            'resultWinners' => $resultWinners,
            'results' => $results,
            'compoCount' => $compoCount,
            'totalCompo' => $totalCompo,
            'totalBelligerants' => $totalBelligerants
        ]);
    }
}
