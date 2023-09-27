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
use App\Service\GameManager;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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

    #[Route('/resultats')]
    public function index(): Response
    {
        $games = $this->entityManager->getRepository(Game::class)->findAll();

        return $this->render('resultat/index.html.twig');
    }

    #[Route('/resultat/{id}', name: 'resultat_new', methods: ['GET'])]
    public function edit(?int $id = null)
    {
       /* $game = null;
        if ($id = null) {
            $game = $entityManager->getRepository(Game::class)->find($id);
	}*/
        $form = $this->initForm();
        return $this->render('resultat/edit.html.twig', ['form' => $form]);
    }

    #[Route('/resultat', name: 'resultat_post', methods: ['POST'])]
    public function save(Request $request)
    {   
        $form = $this->initForm(null);
        $form->handleRequest($request);
        
        $this->gameManager->saveOrUpdate($form->getData());
        
    }

    private function initForm(?Game $game=null)
    {
        $resultat = new ResultatDTO($game);
        return $this->createFormBuilder($resultat)
            ->add('date', DateType::class)
            ->add('joueur1', EntityType::class, ['class' => Joueur::class, 'choice_label' => 'Nom'])
            ->add('joueur2', EntityType::class, ['class' => Joueur::class, 'choice_label' => 'Nom'])
            ->add('vainqueur1', CheckboxType::class, ['label' => 'Joueur 1 Vainqueur', 'required' => false])
            ->add('vainqueur2', CheckboxType::class, ['label' => 'Joueur 2 Vainqueur', 'required' => false])
            ->add('scoreJoueur1', IntegerType::class, ['label' => 'Score joueur 1', 'required' => false])
            ->add('scoreJoueur2', IntegerType::class, ['label' => 'Score joueur 2', 'required' => false])
            ->add('guilde1', EntityType::class, ['class' => Guilde::class, 'choice_label' => 'Nom'])
            ->add('guilde2', EntityType::class, ['class' => Guilde::class, 'choice_label' => 'Nom'])
            ->add('missionControle', EntityType::class, ['class' => MissionControle::class, 'choice_label' => 'Nom'])
            ->add('missionCombat', EntityType::class, ['class' => MissionCombat::class, 'choice_label' => 'Nom'])
            ->add('personnage1Joueur1', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom'])
            ->add('personnage2Joueur1', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom'])
            ->add('personnage3Joueur1', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom'])
            ->add('personnage4Joueur1', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom'])
            ->add('personnage5Joueur1', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom'])
            ->add('personnage1Joueur2', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom'])
            ->add('personnage2Joueur2', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom'])
            ->add('personnage3Joueur2', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom'])
            ->add('personnage4Joueur2', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom'])
            ->add('personnage5Joueur2', EntityType::class, ['class' => Personnage::class, 'choice_label' => 'Nom'])
            ->add('rixe', CheckboxType::class, ['label' => 'Rixe', 'required' => false])
            ->add('save', SubmitType::class, ['label'=>'Enregistrer'])
            ->getForm();
        
    }
}
