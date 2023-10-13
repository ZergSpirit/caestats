<?php
// src/Controller/ResultatController.php
namespace App\Controller\Resultats;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Game;
use App\Entity\Joueur;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Controller\Resultats\ResultatDTO;
use App\Entity\Guilde;
use App\Entity\MissionCombat;
use App\Entity\MissionControle;
use App\Entity\Personnage;
use App\Entity\Tournoi;
use App\Service\GameManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class ResultatController extends AbstractController
{

    /**
     * Construct avec injection
     */
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly GameManager $gameManager)
    {
        
    }

    #[Route('/resultats', name: 'resultat_list')]
    public function index(): Response
    {
        $games = $this->entityManager->getRepository(Game::class)->findAll();
        return $this->render('resultat/index.html.twig', ['games' => $games]);
    }

    #[Route('/resultat/{id}', name: 'resultat_edit', methods: ['GET'])]
    public function edit(?int $id = null)
    {
        $game = null;
        if ($id != null) {
            $game = $this->entityManager->getRepository(Game::class)->find($id);
            if ($game == null) {
                throw new \Exception('Game '.$id.' not found');
            }
        }
        $form = $this->initForm($game);
        return $this->render('resultat/edit.html.twig', ['form' => $form]);
    }

    #[Route('/resultat/{id}', name: 'resultat_post', methods: ['POST'])]
    public function save(Request $request, ?int $id = null)
    {   
        $game = null;
        if ($id != null) {
            $game = $this->entityManager->getRepository(Game::class)->find($id);
            if ($game == null) {
                throw new \Exception('Game '.$id.' not found');
            }
        }
        $form = $this->initForm($game);
        $form->handleRequest($request);
        
        $this->gameManager->saveOrUpdate($form->getData());

        return $this->redirect('/resultats');
        
    }

    private function initForm(?Game $game=null)
    {
        $resultat = new ResultatDTO($game);
        return $this->createFormBuilder($resultat)
            ->add('gameId', HiddenType::class)
            ->add('date', DateType::class, ['widget' => 'single_text'])
            ->add('joueur1', EntityType::class, ['class' => Joueur::class, 'query_builder' => function (EntityRepository $er): QueryBuilder {return $er->createQueryBuilder('j')->orderBy('j.nom', 'ASC');}, 'choice_label' => 'Nom', 'empty_data' => ''])
            ->add('joueur2', EntityType::class, ['class' => Joueur::class, 'query_builder' => function (EntityRepository $er): QueryBuilder {return $er->createQueryBuilder('j')->orderBy('j.nom', 'ASC');}, 'choice_label' => 'Nom', 'empty_data' => ''])
            ->add('scoreJoueur1', IntegerType::class, ['label' => 'Score joueur 1', 'required' => false])
            ->add('scoreJoueur2', IntegerType::class, ['label' => 'Score joueur 2', 'required' => false])
            ->add('guilde1', EntityType::class, ['class' => Guilde::class, 'choice_label' => 'Nom', 'empty_data' => ''])
            ->add('guilde2', EntityType::class, ['class' => Guilde::class, 'choice_label' => 'Nom', 'empty_data' => ''])
            ->add('missionControle', EntityType::class, ['class' => MissionControle::class, 'choice_label' => 'Nom', 'empty_data' => ''])
            ->add('missionCombat', EntityType::class, ['class' => MissionCombat::class, 'choice_label' => 'Nom', 'empty_data' => ''])
            ->add('personnage1Joueur1', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom', 'empty_data' => ''])
            ->add('personnage2Joueur1', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom', 'empty_data' => ''])
            ->add('personnage3Joueur1', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom', 'empty_data' => ''])
            ->add('personnage4Joueur1', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom', 'empty_data' => ''])
            ->add('personnage5Joueur1', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom', 'empty_data' => '', 'required'=>false])
            ->add('personnage1Joueur2', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom', 'empty_data' => ''])
            ->add('personnage2Joueur2', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom', 'empty_data' => ''])
            ->add('personnage3Joueur2', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom', 'empty_data' => ''])
            ->add('personnage4Joueur2', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom', 'empty_data' => ''])
            ->add('personnage5Joueur2', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom', 'empty_data' => '', 'required'=>false])
            ->add('rixe', CheckboxType::class, ['label' => 'Rixe', 'required' => false])
            ->add('noRanking', CheckboxType::class, ['label' => 'Résultat pas pris en compte dans le classement', 'required' => false])
            ->add('noStats', CheckboxType::class, ['label' => 'Compo pas prise en compte dans les stats', 'required' => false])
            ->add('tournoi', EntityType::class, ['class'=>Tournoi::class,'choice_label'=>'Nom'])
            ->add('ronde', IntegerType::class, ['label' => 'Ronde', 'required' => false])
            ->add('save', SubmitType::class, ['label'=>'Enregistrer'])
            ->getForm();
        
    }
}
