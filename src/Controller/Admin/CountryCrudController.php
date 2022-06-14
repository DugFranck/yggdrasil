<?php

namespace App\Controller\Admin;

use App\Entity\Country;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CountryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Country::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('iso'),
            TextField::new('name'),
            TextField::new('nicename'),
            IntegerField::new('iso3'),
            IntegerField::new('numcode'),
            IntegerField::new('phonecode'),
            TextField::new('groupe'),
            AssociationField::new('zone')


        ];
    }

}
