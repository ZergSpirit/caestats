<?php

namespace App\Controller;

use App\Entity\Belligerant;
use App\Entity\Compo;
use App\Entity\Game;
use App\Entity\Guilde;
use App\Entity\Joueur;
use App\Entity\MissionCombat;
use App\Entity\MissionControle;
use App\Entity\Personnage;
use App\Entity\Tournoi;
use App\Service\TournoiManager;
use Doctrine\Common\Collections\ArrayCollection;
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
use Symfony\Component\Validator\Constraints\Length;

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
        $this->tournoiManager->createRound($this->entityManager->getRepository(Tournoi::class)->find($tournoiId), $dto->getMissionControle(), $dto->getMissionCombat(), $dto->isRixe(), $belligerants, $dto->isUseSeeds());
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

        $leftOutPlayer = null;
        //Nombre de joueurs impair, on calcul qui ne joue pas à cette ronde
        if($tournoi->getRanks()->count() % 2 > 0){
            foreach($tournoi->getRanks() as $rank){
                $leftOutPlayer = $rank->getJoueur();
                $nb = 1;
                foreach($games as $game){
                    if($game->getBelligerant1()->getJoueur()->getId() == $leftOutPlayer->getId()){
                        $leftOutPlayer = null;
                        break;
                    }
                    else if($game->getBelligerant2()->getJoueur()->getId() == $leftOutPlayer->getId()){
                        $leftOutPlayer = null;
                        break;
                    }
                }
                if($leftOutPlayer != null){
                    break;
                }
            }
        }

        
        return $this->render('tournoi/ronde.html.twig', [
            'controller_name' => 'TournoiController',
            'tournoi' => $tournoi,
            'games' => $games,
            'ronde' => $ronde,
            'leftOutPlayer' => $leftOutPlayer,
            'missionControles' => $this->entityManager->getRepository(MissionControle::class)->findAll(),
            'missionCombats' => $this->entityManager->getRepository(MissionCombat::class)->findAll()
        ]);
    }

    #[Route('/tournoi/{tournoiId}/{ronde}/nextRonde', name: 'app_tournoi_nextRonde', methods: ['POST'])]
    public function createNextRonde(int $tournoiId, int $ronde, Request $request): Response
    {  

        $this->updateRonde($request, $tournoiId, $ronde);

        $missionControle = $this->entityManager->getRepository(MissionControle::class)->find($request->request->get("missionControleId")); 
        $missionCombat = $this->entityManager->getRepository(MissionCombat::class)->find($request->request->get("missionCombatId")); 

        $this->tournoiManager->createRound($this->entityManager->getRepository(Tournoi::class)->find($tournoiId), $missionControle, $missionCombat, true, array(), true);
    
        return $this->render('tournoi/index.html.twig', [
            
        ]);
    }

    #[Route('/tournoi/{tournoiId}/{ronde}', name: 'app_tournoi_post_ronde', methods: ['POST'])]
    public function postRonde(Request $request, ?int $tournoiId, ?int $ronde): Response
    {
        dd($request->request->all());
        $this->updateRonde($request, $tournoiId, $ronde);

        return $this->redirect("/tournoi/".$tournoiId);
    }

    private function updateRonde(Request $request, ?int $tournoiId, ?int $ronde){
        $tournoi = $this->entityManager->getRepository(Tournoi::class)->find($tournoiId);
        $games = $this->entityManager->getRepository(Game::class)->findAllByCriteria(array(
            "tournoi" => $tournoi,
            "ronde" => $ronde
        ));

        foreach($games as $game){
            $this->completeBelligerantFromRequest($request, $game->getBelligerant1());
            $this->completeBelligerantFromRequest($request, $game->getBelligerant2());
            if($game->getBelligerant1()->getScore() == null || $game->getBelligerant2()->getScore() == null){
                $game->setVainqueur(null);
            }
            if($game->getBelligerant1()->getScore() == $game->getBelligerant2()->getScore()){
                $game->setVainqueur(null);
            }
            else if($game->getBelligerant1()->getScore() > $game->getBelligerant2()->getScore()){
                $game->getBelligerant1()->setVainqueur(true);
                $game->getBelligerant2()->setVainqueur(false);
                $game->setVainqueur($game->getBelligerant1()->getJoueur());
            }
            else {
                $game->getBelligerant1()->setVainqueur(false);
                $game->getBelligerant2()->setVainqueur(true);
                $game->setVainqueur($game->getBelligerant2()->getJoueur());
            }
            $this->entityManager->getRepository(Game::class)->save($game);
            $this->entityManager->getRepository(Belligerant::class)->save($game->getBelligerant1());
            $this->entityManager->getRepository(Belligerant::class)->save($game->getBelligerant2());
        }
        $this->tournoiManager->updateRanks($tournoi);
    }

    private function completeBelligerantFromRequest(Request $request, Belligerant $belligerant){
        $personnages = $request->request->all('belligerant'.$belligerant->getId().'Personnages');
        $score = $request->request->get('belligerant'.$belligerant->getId().'Score');
        $belligerant->setScore(intval($score));
        $compo = $belligerant->getCompo();
        $compo->getPersonnages()->clear();
        foreach($personnages as $key=>$value){
            $compo->addPersonnage($this->entityManager->getRepository(Personnage::class)->find($value));
        }
        $this->entityManager->getRepository(Compo::class)->save($compo);
        $this->entityManager->getRepository(Belligerant::class)->save($belligerant);
    }


    #[Route('/tournoi/{tournoiId}/{ronde}/delete', name: 'app_tournoi_ronde_delete', methods: ['GET'])]
    public function deleteRonde(int $tournoiId, ?int $ronde): Response
    {
        $tournoi = $this->entityManager->getRepository(Tournoi::class)->find($tournoiId);
        $this->entityManager->getRepository(Game::class)->deleteGames($tournoi, $ronde);
        if($ronde == 1){
            $tournoi->getRanks()->clear();
            $this->entityManager->getRepository(Tournoi::class)->save($tournoi);
        }
        return $this->redirect("/tournoi/".$tournoiId);
    }

    #[Route('/tournoi/{tournoiId}/{ronde}/reshuffle', name: 'app_tournoi_ronde_reshuffle', methods: ['GET'])]
    public function reshuffleRonde(int $tournoiId, ?int $ronde): Response
    {
        $tournoi = $this->entityManager->getRepository(Tournoi::class)->find($tournoiId);
        $games = $this->entityManager->getRepository(Game::class)->findGames($tournoi,$ronde);
        $belligerants = [];
        $missionControle = null;
        $missionCombat = null;
        $rixe = null;
        foreach($games as $game){
            $missionControle = $game->getMissionControle();
            $missionCombat = $game->getMissionCombat();
            $rixe = $game->isRixe();
            array_push($belligerants, (object)[
                'joueur' => $game->getBelligerant1()->getJoueur(),
                'guilde' => $game->getBelligerant1()->getCompo()->getGuilde()
            ]);
            array_push($belligerants, (object)[
                'joueur' => $game->getBelligerant2()->getJoueur(),
                'guilde' => $game->getBelligerant2()->getCompo()->getGuilde()
            ]);
        }
        $this->entityManager->getRepository(Game::class)->deleteGames($tournoi, $ronde);
        $this->tournoiManager->createRound($tournoi, $missionControle, $missionCombat, $rixe, $belligerants, $tournoi->isUseSeeds(), false);

        return $this->redirect("/tournoi/".$tournoiId);
    }

    private function initForm(?Tournoi $tournoi = null){
        $dto = new TournoiDTO($tournoi);
        if($dto->getJoueurs() != null){
            $allJoueursGames = [];
            foreach($dto->getJoueurs() as $joueur){
                $allJoueursGames[$joueur->getId()] = $this->entityManager->getRepository(Game::class)->countGames($joueur, $tournoi);
            }
            $dto->setJoueurCountGames($allJoueursGames);
        }
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
