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
use App\Entity\Belligerant;
use App\Entity\Compo;
use App\Entity\Guilde;
use App\Entity\MissionCombat;
use App\Entity\MissionControle;
use App\Entity\Personnage;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class ResultatController extends AbstractController
{
    #[Route('/resultats')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $games = $entityManager->getRepository(Game::class)->findAll();

        return $this->render('resultat/index.html.twig');
    }

    #[Route('/resultat/{id}', name: 'resultat_new', methods: ['GET'])]
    public function edit(EntityManagerInterface $entityManager, ?int $id = null)
    {
       /* $game = null;
        if ($id = null) {
            $game = $entityManager->getRepository(Game::class)->find($id);
	}*/
        $form = $this->initForm();
        return $this->render('resultat/edit.html.twig', ['form' => $form]);
    }

    #[Route('/resultat', name: 'resultat_post', methods: ['POST'])]
    public function save(EntityManagerInterface $entityManager, Request $request)
    {   
        $form = $this->initForm(null);
        $form->handleRequest($request);
        /**
         * @var $data ResultatDTO
         */
        $data = $form->getData();
        $game = new Game();
        $game->setRixe($data->isRixe());
        $belligerant1 = new Belligerant();
        $belligerant1->setJoueur($entityManager->getRepository(Joueur::class)->find($data->getJoueur1()));
        $belligerant1->setScore($data->getScoreJoueur1());
        if ($data->isVainqueur1()) {
            $belligerant1->setVainqueur(true);
        }
        $compo1 = new Compo();
        $compo1->setGuilde($entityManager->getRepository(Guilde::class)->find($data->getGuilde1()));
        $compo1->getPersonnages()->add($entityManager->getRepository(Personnage::class)->find($data->getPersonnage1Joueur1()));
        $compo1->getPersonnages()->add($entityManager->getRepository(Personnage::class)->find($data->getPersonnage2Joueur1()));
        $compo1->getPersonnages()->add($entityManager->getRepository(Personnage::class)->find($data->getPersonnage3Joueur1()));
        $compo1->getPersonnages()->add($entityManager->getRepository(Personnage::class)->find($data->getPersonnage4Joueur1()));
        $compo1->getPersonnages()->add($entityManager->getRepository(Personnage::class)->find($data->getPersonnage5Joueur1()));
        $belligerant1->setCompo($compo1);

        $belligerant2 = new Belligerant();
        $belligerant2->setJoueur($entityManager->getRepository(Joueur::class)->find($data->getJoueur2()));
        $belligerant2->setScore($data->getScoreJoueur2());
        if ($data->isVainqueur2()) {
            $belligerant2->setVainqueur(true);
        }
        $compo2 = new Compo();
        $compo2->setGuilde($entityManager->getRepository(Guilde::class)->find($data->getGuilde2()));
        $compo2->getPersonnages()->add($entityManager->getRepository(Personnage::class)->find($data->getPersonnage1Joueur2()));
        $compo2->getPersonnages()->add($entityManager->getRepository(Personnage::class)->find($data->getPersonnage1Joueur2()));
        $compo2->getPersonnages()->add($entityManager->getRepository(Personnage::class)->find($data->getPersonnage1Joueur2()));
        $compo2->getPersonnages()->add($entityManager->getRepository(Personnage::class)->find($data->getPersonnage1Joueur2()));
        $compo2->getPersonnages()->add($entityManager->getRepository(Personnage::class)->find($data->getPersonnage1Joueur2()));
        $belligerant2->setCompo($compo2);

        $game->setBelligerant1($belligerant1);
        $game->setBelligerant2($belligerant2);

        $game->setMissionCombat($entityManager->getRepository(MissionCombat::class)->find($data->getMissionCombat()));
        $game->setMissionControle($entityManager->getRepository(MissionControle::class)->find($data->getMissionControle()));
        $game->setDate($data->getDate());

        $em = $entityManager->getRepository(Game::class);
        $em->save($game);
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
