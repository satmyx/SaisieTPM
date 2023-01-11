<?php

namespace App\Controller\Admin;

use App\Entity\Formulaire;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FormulaireCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Formulaire::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            AssociationField::new('relation'),
        ];
    }
    
}
