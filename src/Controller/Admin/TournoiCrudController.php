<?php

namespace App\Controller\Admin;

use App\Entity\Tournoi;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TournoiCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tournoi::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            DateField::new('date'),
            TextField::new('ville'),
            BooleanField::new('online'),
            IntegerField::new('nbParticipants'),
            BooleanField::new('finished'),
            BooleanField::new('notRanked'),
            BooleanField::new('managedByCaestats')
        ];
    }
    
}
