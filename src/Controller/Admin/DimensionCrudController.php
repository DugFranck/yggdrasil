<?php

namespace App\Controller\Admin;

use App\Entity\Dimension;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DimensionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Dimension::class;
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
