<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Guilde;
use App\Entity\Joueur;
use App\Entity\MissionCombat;
use App\Entity\MissionControle;
use App\Entity\Tournoi;
use App\Service\TournoiManager;
use Doctrine\DBAL\Types\DateType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TournoiController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager, private TournoiManager $tournoiManager ){}

    #[Route('/tournoi/{tournoiId}', name: 'app_tournoi_post', methods: ['POST'])]
    public function createFirstRonde(int $tournoiId, Request $request): Response
    {   
        $belligerantRq = ($request->request->all('belligerants'));
        $belligerants = [];
        foreach($belligerantRq as $key => $value){
            $arr = explode("-",$value);
            array_push($belligerants, (object)[
                'joueur' => $this->entityManager->getRepository(Joueur::class)->find($arr[0]),
                'guilde' => $this->entityManager->getRepository(Guilde::class)->find($arr[1])
            ]);
        }


        $form = $this->initForm();
        $form->handleRequest($request);
        $dto = $form->getData();
        $this->tournoiManager->createFirstRound($this->entityManager->getRepository(Tournoi::class)->find($tournoiId), $dto->getMissionControle(), $dto->getMissionCombat(), $dto->isRixe(), $belligerants, $dto->isUseSeeds());
        return $this->redirect("/tournoi/".$tournoiId);
    }

    #[Route('/tournoi/{tournoiId}', name: 'app_tournoi', methods: ['GET'])]
    public function index(int $tournoiId, ?int $ronde): Response
    {   
        $tournoi = $this->entityManager->getRepository(Tournoi::class)->find($tournoiId);
        $form = $this->initForm($tournoi);
        $dto = $form->getData();
        $rondes = $this->entityManager->getRepository(Tournoi::class)->getRondes($tournoi);
        return $this->render('tournoi/index.html.twig', [
            'controller_name' => 'TournoiController',
            'tournoi' => $tournoi,
            'rondes' => $rondes,
            'form' => $form,
            'dto' => $dto,
            'joueurs' => $this->entityManager->getRepository(Joueur::class)->findBy(array(),array('nom' => 'asc')),
            'guildes' => $this->entityManager->getRepository(Guilde::class)->findBy(array(),array('nom' => 'asc'))
        ]);
    }

    #[Route('/tournoi/{tournoiId}/{ronde}', name: 'app_tournoi_ronde', methods: ['GET'])]
    public function ronde(int $tournoiId, ?int $ronde): Response
    {
        $tournoi = $this->entityManager->getRepository(Tournoi::class)->find($tournoiId);
        $games = $this->entityManager->getRepository(Game::class)->findAllByCriteria(array(
            "tournoi" => $tournoi,
            "ronde" => $ronde
        ));
        
        return $this->render('tournoi/ronde.html.twig', [
            'controller_name' => 'TournoiController',
            'tournoi' => $tournoi,
            'games' => $games,
            'ronde' => $ronde
        ]);
    }


    #[Route('/tournoi/{tournoiId}/{ronde}/delete', name: 'app_tournoi_ronde_delete', methods: ['GET'])]
    public function deleteRonde(int $tournoiId, ?int $ronde): Response
    {
        $tournoi = $this->entityManager->getRepository(Tournoi::class)->find($tournoiId);
        $this->entityManager->getRepository(Game::class)->deleteGames($tournoi, $ronde);
        return $this->redirect("/tournoi/".$tournoiId);
    }

    private function initForm(?Tournoi $tournoi = null){
        $dto = new TournoiDTO($tournoi);
        return $this->createFormBuilder($dto )
            ->add('id', HiddenType::class)
            ->add('useSeeds', CheckboxType::class, ['label' => 'Utiliser les seeds', 'required' => false])
            ->add('rixe', CheckboxType::class, ['label' => 'Rixe', 'required' => false])
            ->add('missionControle', EntityType::class, ['label' => 'Mission controle', 'class'=>MissionControle::class, 'query_builder' => function (EntityRepository $er): QueryBuilder {return $er->createQueryBuilder('m')->orderBy('m.Nom', 'ASC');},'choice_label'=>'Nom', 'required' => true])
            ->add('missionCombat', EntityType::class, ['label' => 'Mission combat', 'class'=>MissionCombat::class, 'query_builder' => function (EntityRepository $er): QueryBuilder {return $er->createQueryBuilder('m')->orderBy('m.Nom', 'ASC');},'choice_label'=>'Nom', 'required' => true])
            ->add('save', SubmitType::class, ['label'=>'Lancer la première ronde'])
            ->getForm();
    }
}
