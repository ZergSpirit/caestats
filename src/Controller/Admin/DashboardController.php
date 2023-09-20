<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use App\Entity\Personnage;
use App\Entity\Guilde;
use App\Entity\Joueur;
use App\Entity\Compo;
use App\Entity\Tournoi;
use App\Entity\MissionControle;
use App\Entity\MissionCombat;

class DashboardController extends AbstractDashboardController
{

    public function __construct(private AdminUrlGenerator $adminUrlGenerator){}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
         // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
        //return parent::index();
        $url = $this->adminUrlGenerator->setController(PersonnageCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Caestats');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Caeris');
        yield MenuItem::linkToCrud('Personnages', 'fa fa-tags', Personnage::class)->setAction(Crud::PAGE_INDEX);
        yield MenuItem::linkToCrud('Guildes', 'fa fa fa-tags', Guilde::class)->setAction(Crud::PAGE_INDEX);
        yield MenuItem::section('Missions');
        yield MenuItem::linkToCrud('Missions de controles', 'fa fa-tags', MissionControle::class)->setAction(Crud::PAGE_INDEX);
        yield MenuItem::linkToCrud('Missions de combat', 'fa fa-tags', MissionCombat::class)->setAction(Crud::PAGE_INDEX);
        yield MenuItem::section('Résultats');
        yield MenuItem::linkToCrud('Joueurs', 'fa fa-tags', Joueur::class)->setAction(Crud::PAGE_INDEX);
    }
}
