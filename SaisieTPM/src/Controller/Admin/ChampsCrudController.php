<?php

namespace App\Controller\Admin;

use App\Entity\Champs;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ChampsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Champs::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('Nom'),
            AssociationField::new('id_type'),
        ];
    }
    
}
