<?php

namespace App\Form;

use App\Entity\OrderItem;
use App\Entity\ProductDimensionStock;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SelectDimensionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $product=$options['product'];

        $builder
            ->add('dimension',EntityType::class,[
                'mapped'=>false,
                    'class'=>ProductDimensionStock::class,
                    'query_builder'=>function(EntityRepository $entityRepository) use ($product)
                    {
                        return $entityRepository->createQueryBuilder("pds")

                            ->join("pds.product","p")
                            ->where("p.slug = :slug")
                            ->setParameter(":slug",$product->getSlug());

                    }
                ])
            ->add('quantity', NumberType::class,[
                'constraints'=>[
                    new NotBlank()
                ]
            ])
            ->add('submit', SubmitType::class,[
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
           'product'=>null,
            'data_class' => OrderItem::class,
        ]);
    }
}
