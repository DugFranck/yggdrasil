<?php
namespace App\Form;
use App\Classe\Search;


use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('string', TextType::class,[
                    'label'=> false,
                    'required' => false,
                    'attr' => [
                        'class'=>'form-control-sm',
                        'placeholder'=>'Votre recherche...'

                    ]
                ])
            ->add('categories', EntityType::class,[
                'label'=> false,
                'required'=>false,
                'class'=> Category::class,
                'multiple'=>true,
                'expanded'=>true

            ])
            ->add('submit',SubmitType::class,[
                'label'=>'Filtrer',
                'attr'=> [
                    'class'=>'btn btn-black py-3 px-5'
                ]
            ])
            ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'crf_protection' => false,
            'csrf_protection' => false,
            "allow_extra_fields" => true,
            'csrf_token' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}