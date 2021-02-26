<?php

namespace App\Controller\Admin;

use App\Entity\Agence;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AgenceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Agence::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('nomAgence'),
            TextField::new('Adresse'),
            TextField::new('Telephone'),
            TextField::new('lattitude'),
            TextField::new('longitude'),
        ];
    }
    
}
