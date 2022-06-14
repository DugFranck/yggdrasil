<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('new_password', RepeatedType::class,[
                'type' => PasswordType::class,

                'attr' => [
                    'placeholder' => 'Merci de saisir votre mot de passe'
                ],
                'constraints'=>[
                    new NotBlank(),
                    new Regex('/^\S*(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=\S*[\W])[a-zA-Z\d]{8,}\S*$/'),
                ],
                'invalid_message' =>'Le mot de passe et la confirmation doivent être identique.',

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
                'label'=>"Mettre a jour mon mot de passe",
                'attr' => [

                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
