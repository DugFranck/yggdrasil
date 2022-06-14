<?php

namespace App\Form;

use App\Entity\Product;



use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\MoneyType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, [
        'label' => false,

        'attr' => [
            'readonly' => true
        ]
    ])


            ->add('subtitle',TextType::class, [
                'label' => false,

                'attr' => [
                    'readonly' => true
                ]
            ])
            ->add('description',TextareaType::class, [
                'label' => false,

                'attr' => [
                    'readonly' => true,


                ]
            ])
            ->add('price',MoneyType::class, [
                'label' => false,
                'divisor' => 100,


                'attr' => [
                    'readonly' => true,

                ]
            ])

            ->add('productDimensionStocks')

            ->add('submit',SubmitType::class,[
                'label'=>"Ajouter au panier",
                'attr' => [
                    'class' => 'button',

                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
