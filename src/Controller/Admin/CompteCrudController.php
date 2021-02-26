<?php

namespace App\Controller\Admin;

use App\Entity\Compte;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CompteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Compte::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('numero'),
            NumberField::new('solde'),
            AssociationField::new('utilisateurs'),
            AssociationField::new ('agence')
            
        ];
    }
    
}
