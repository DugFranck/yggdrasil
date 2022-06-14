<?php

namespace App\Controller\Admin;

use App\Entity\ProductDimensionStock;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class ProductDimensionStockCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductDimensionStock::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('product'),
            AssociationField::new('dimension'),
            IntegerField::new('poids'),
            IntegerField::new('stock')
        ];
    }

}
