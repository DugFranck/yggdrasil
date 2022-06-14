<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('title','Titre du header'),
            TextField::new('titleGras','Titre du header en gras'),
            TextareaField::new('content','Contenu de notre hearder'),
            TextField::new('surTitle','Texte verticale'),
            TextField::new('surTitreHorizontal','Texte horizontal'),
            TextField::new('BtnTitle','Titre du bouton'),
            TextField::new('BtnUrl','Url de destination de notre bouton'),
            ImageField::new('illustration','illustration: l:1544px par h:1860px')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
        ];
    }

}
