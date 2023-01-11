<?php

namespace App\Controller\Admin;

use App\Entity\TypeChamps;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TypeChampsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeChamps::class;
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
