<?php

namespace App\Controller\Admin;

use App\Entity\Personnage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class PersonnageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Personnage::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('Nom'),
            TextField::new('Code'),
            BooleanField::new('ethere'),
            AssociationField::new('guildes'),
        ];
    }
    
}
