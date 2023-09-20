<?php

namespace App\Controller\Admin;

use App\Entity\Tournoi;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TournoiCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tournoi::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
