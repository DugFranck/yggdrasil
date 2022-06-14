<?php

namespace App\Controller\Admin;

use App\Entity\PriceSending;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class PriceSendingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PriceSending::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('carrier','transporteur'),
            AssociationField::new('zone'),
            IntegerField::new('poids'),
            MoneyField::new('price')->setCurrency('EUR'),
        ];
    }

}
