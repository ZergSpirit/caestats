<?php

namespace App\Controller\Admin;

use App\Entity\MissionControle;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MissionControleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MissionControle::class;
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
