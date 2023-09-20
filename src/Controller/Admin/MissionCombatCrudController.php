<?php

namespace App\Controller\Admin;

use App\Entity\MissionCombat;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MissionCombatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MissionCombat::class;
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
