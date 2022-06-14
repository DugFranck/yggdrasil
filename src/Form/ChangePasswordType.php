<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                'disabled' => true,
                'label' => 'Mon adresse email'
            ])

            ->add('firstname', TextType::class,[
                'disabled' => true,
                'label' => 'Mon prénom'
            ])
            ->add('lastname',TextType::class,[
                'disabled' => true,
                'label' => 'Mon nom'
            ])

            ->add('old_password', PasswordType::class,[
                'label' => 'Mon mot de actuel',
                'mapped'=>false,

                'attr' => [
                    'placeholder' => 'Merci de saisir votre mot de passe'
                ],
                'constraints'=>[
                    new NotBlank(),
                    new Regex('/^\S*(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=\S*[\W])[a-zA-Z\d]{8,}\S*$/'),
                ],
            ])
            ->add('new_password', RepeatedType::class,[
                'type' => PasswordType::class,
                'mapped'=>false,
                'constraints'=>[
                    new NotBlank()
                ],
                'invalid_message' =>'Le mot de passe et la confirmation doivent être identique.',
                'label' => 'Mon nouveau mot de passe',
                'required'=>true,
                'first_options'=>[
                    'label'=>'Mon nouveau mot de passe doit contenir au minimum 8 caractères :
                                avec au moins une lettre minuscule et une lettre majuscule, un caractère spécial et un chiffre',
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre  nouveau mot de passe'
                    ]
                ],
                'second_options'=>[
                    'label'=>'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de confirmer votre nouveau mot de passe'
                    ]

                ]

            ])
            ->add('submit',SubmitType::class,[
                'label'=>"Mettre a jour"

            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
