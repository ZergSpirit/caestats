<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\Rank;
use App\Entity\Tournoi;
use App\Service\ZitsManager;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ZitsController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private ZitsManager $zitsManager){

    }
    
    #[Route('/zits/recalculate', name: 'app_zits_recalculate')]
    public function recalculate(): Response
    {   

        $this->zitsManager->recalculateAllZits();
        
        return $this->render('zits/index.html.twig', [
            'controller_name' => 'ZitsController',
            'tournois' => $this->entityManager->getRepository(Tournoi::class)->findBy(array(), array('date' => 'DESC'))
        ]);
    }

    #[Route('/zits', name: 'app_zits')]
    public function index(): Response
    {

        return $this->render('zits/index.html.twig', [
            'controller_name' => 'ZitsController',
            'tournois' => $this->entityManager->getRepository(Tournoi::class)->findBy(array(), array('date' => 'DESC'))
        ]);
    }

    #[Route('/zits/{id}', name: 'app_zits_edit', methods: ['GET'])]
    public function edit(?int $id = null): Response
    {
        $tournoi = $this->entityManager->getRepository(Tournoi::class)->find($id);

        return $this->render('zits/edit.html.twig', [
            'controller_name' => 'ZitsController',
            'tournoi' => $tournoi,
            'joueurs' => $this->entityManager->getRepository(Joueur::class)->findBy(array(),array('nom'=>'asc'))
        ]);
    }

    #[Route('/zits/{id}', name: 'app_zits_post', methods: ['POST'])]
    public function post(?int $id = null, Request $request): Response
    {   
        foreach ($request->request->all() as $var){
            $joueurs = $var;
            break;
        }
        $tournoi = $this->entityManager->getRepository(Tournoi::class)->find($id);
        foreach ($tournoi->getRanks() as $rank) {
            $tournoi->removeRank($rank);
        }
        $this->entityManager->getRepository(Tournoi::class)->save($tournoi);

        $i = 1;
        foreach ($joueurs as $id) {
            if($id == null){
                continue;
            }
            $rank = new Rank();
            $rank->setTournoi($tournoi);
            $rank->setPosition($i);
            $rank->setJoueur($this->entityManager->getRepository(Joueur::class)->find($id));
            $this->entityManager->getRepository(Rank::class)->save($rank);
            $i++;
        }   
        $tournoi->setNbParticipants($i);
        $tournoi->setFinished(true);
        $this->entityManager->getRepository(Tournoi::class)->save($tournoi);

        return $this->redirect('/zits');
    }


}
