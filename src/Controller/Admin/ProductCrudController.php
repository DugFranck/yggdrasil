<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\AttachmentType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $imageFile = ImageField::new('thumbnailFaile')->setFormType(VichImageType::class);
        $image = ImageField::new('thumbnail')->setBasePath('images/thumbnails');
        return [
            TextField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name'),
            ImageField::new('illustration', 'illustration: l : 800px par h : 1200px',)
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            CollectionField::new('attachments')

                ->setEntryType(AttachmentType::class)
                ->setFormTypeOption('by_reference', false)
                ->onlyOnForms(),
            CollectionField::new('attachments')
                ->setTemplatePath('images.html.twig')
                ->onlyOnDetail(),
            TextField::new('subtitle'),
            TextareaField::new('description'),
            BooleanField::new('isBest'),

            /*BooleanField::new('isBest'),*/
            MoneyField::new('price')->setCurrency('EUR'),
            AssociationField::new('category')
        ];

        if ($pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL) {
            $fields[] = $image;
        } else {
            $fields[] = $imageFile;
        }
        return $fields;

    }
    Public function configureActions(Actions $actions): Actions
    {
        return $actions->add('index','detail');
    }

}
