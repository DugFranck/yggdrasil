<?php
namespace App\Form;



use App\Classe\SearchNews;

use App\Entity\CategoryNews;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchNewsType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('categories', EntityType::class,[
                'label'=> false,
                'required'=>false,
                'class'=> CategoryNews::class,
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
            'data_class' => SearchNews::class,
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