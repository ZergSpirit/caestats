<?php

namespace App\Controller;

use App\Controller\Resultats\ResultatDTO;
use App\Entity\Game;
use App\Entity\Guilde;
use App\Entity\Joueur;
use App\Entity\MissionCombat;
use App\Entity\Personnage;
use App\Entity\Tournoi;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\QueryBuilder;
use Proxies\__CG__\App\Entity\MissionControle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class StatsGameController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
        
    }

    #[Route('/stats/game', name: 'app_stats_game', methods: ['GET'])]
    public function index(#[MapQueryParameter] ?int $joueurId = null, #[MapQueryParameter] ?int $tournoiId = null, #[MapQueryParameter] ?int $guildeId = null, #[MapQueryParameter] ?string $guildeCode = null, #[MapQueryParameter] ?string $compoCode = null): Response
    {   

        $dto = new StatsGameDTO();
        $joueur = null;
        if ($joueurId != null) {
            $joueur = $this->entityManager->getRepository(Joueur::class)->find($joueurId);
            $dto->setJoueur1($joueur);
        } 
        $tournoi = null;
        if ($tournoiId != null) {
            $tournoi = $this->entityManager->getRepository(Tournoi::class)->find($tournoiId);
            $dto->setTournoi($tournoi);
        } 
        $guilde = null;
        if ($guildeId != null) {
            $guilde = $this->entityManager->getRepository(Guilde::class)->find($guildeId);
            $dto->setGuilde1($guilde);
        }
        if ($guildeCode != null) {
            $guilde = $this->entityManager->getRepository(Guilde::class)->findOneBy(['code' => $guildeCode]);
            $dto->setGuilde1($guilde);
        }
        if ($compoCode != null) {
            $guildeCode = explode("_", $compoCode);
            $guilde = $this->entityManager->getRepository(Guilde::class)->findOneBy(['code' => $guildeCode[0]]);
            $dto->setGuilde1($guilde);
            $explodedString = explode("_", $compoCode);
            $dto->setPersonnageJoueur1(new ArrayCollection());
            foreach (explode("-", $explodedString[1]) as $persoCode) {
                $dto->getPersonnageJoueur1()->add($this->entityManager->getRepository(Personnage::class)->findOneBy(['code' => $persoCode]));
            }
        }
       
        $games = $this->entityManager->getRepository(Game::class)->findAllByCriteria($joueur, $tournoi, $guilde, $dto->getPersonnageJoueur1());
        $form = $this->initForm($dto);
        
        return $this->render('stats_game/index.html.twig', [
            'controller_name' => 'StatsGameController',
            'form' => $form,
            'result_stats' => null,
            'dto' => $dto,
            'games' => $games
        ]);
    }

    #[Route('/stats/game', name: 'app_stats_game_post', methods: ['POST'])]
    public function search(Request $request)
    {   
        $form = $this->initForm();
        $form->handleRequest($request);
        $dto = $form->getData();
//?Joueur $joueur, ?Tournoi $tournoi, ?Guilde $guilde, ?ArrayCollection $compo = null, ?bool $rixe=null, ?MissionControle $missionControle=null, ?MissionCombat $missionCombat=null, ?Joueur $joueur2 = null, ?Guilde $guilde2= null, ?ArrayCollection $compo2= null
        $results = $this->entityManager->getRepository(Game::class)->findAllByCriteria($dto->getJoueur1(), $dto->getTournoi(), $dto->getGuilde1(), $dto->getPersonnageJoueur1(), $dto->isRixe(), $dto->getMissionControle(), $dto->getMissionCombat(), $dto->getJoueur2(), $dto->getGuilde2(), $dto->getPersonnageJoueur2());
        $result_stats = [];
        $countTotal = 0;
        $countWins = 0;
        $countTies = 0;
        $countDefeats = 0;
        foreach ($results as $game) {
            $belligerants = $dto->getRelevantBelligerants($game);
            foreach ($belligerants as $belligerant) {
                $countTotal++;
                if ($game->getVainqueur() == null) {
                    $countTies++;
                } else if ($game->getVainqueur()->getId() == $belligerant->getJoueur()->getId()) {
                    $countWins++;
                } else {
                    $countDefeats++;
                }
            }
        }
        $result_stats['wins'] = $countWins;
        $result_stats['defeats'] = $countDefeats;
        $result_stats['ties'] = $countTies;
        $result_stats['total'] = $countTotal;
        return $this->render('stats_game/index.html.twig', [
            'controller_name' => 'StatsGameController',
            'form' => $form,
            'dto' => $dto,
            'games' => $results,
            'result_stats' => $result_stats
        ]);
        
    }


    private function initForm(?StatsGameDTO $dto = null)
    {
        if ($dto == null) {
            $dto = new StatsGameDTO();
        }
        return $this->createFormBuilder($dto)
            ->add('joueur1', EntityType::class, ['class' => Joueur::class, 'query_builder' => function (EntityRepository $er): QueryBuilder {return $er->createQueryBuilder('j')->orderBy('j.nom', 'ASC');}, 'choice_label' => 'Nom', 'empty_data' => '', 'required' => false])
            ->add('joueur2', EntityType::class, ['class' => Joueur::class, 'query_builder' => function (EntityRepository $er): QueryBuilder {return $er->createQueryBuilder('j')->orderBy('j.nom', 'ASC');}, 'choice_label' => 'Nom', 'empty_data' => '', 'required' => false])
            ->add('guilde1', EntityType::class, ['class' => Guilde::class, 'choice_label' => 'Nom', 'empty_data' => '', 'required' => false])
            ->add('guilde2', EntityType::class, ['class' => Guilde::class, 'choice_label' => 'Nom', 'empty_data' => '', 'required' => false])
            ->add('missionControle', EntityType::class, ['class' => MissionControle::class, 'choice_label' => 'Nom', 'empty_data' => '', 'required' => false])
            ->add('missionCombat', EntityType::class, ['class' => MissionCombat::class, 'choice_label' => 'Nom', 'empty_data' => '', 'required' => false])
            ->add('rixe', CheckboxType::class, ['label' => 'Rixe', 'required' => false])
            ->add('tournoi', EntityType::class, ['class'=>Tournoi::class,'choice_label'=>'Nom', 'required' => false])
            ->add('personnageJoueur1', EntityType::class, ['multiple' => true, 'class'=>Personnage::class,'choice_label'=>'Nom', 'required' => false])
            ->add('personnageJoueur2', EntityType::class, ['multiple' => true, 'class'=>Personnage::class,'choice_label'=>'Nom', 'required' => false])
            ->add('save', SubmitType::class, ['label'=>'Rechercher'])
            ->getForm();
    }

}
