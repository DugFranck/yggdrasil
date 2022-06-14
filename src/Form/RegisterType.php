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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname',TextType::class, [
                'label' =>'Votre prénom',
                'constraints'=>[
                    new NotBlank()
                ],
                'attr' => [
                    'placeholder' => 'Merci de saisir votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' =>'Votre nom',
                'constraints'=>[
                    new NotBlank()
                ],
                'attr' => [
                    'placeholder' => 'Merci de saisir votre nom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' =>'Votre email',
                'constraints'=>[
                    new NotBlank()
                ],
                'attr' => [
                    'placeholder' => 'Merci de saisir votre email'
                ]
            ])
            ->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'constraints'=>[
                    new NotBlank(),
                    new Regex('/^\S*(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=\S*[\W])[a-zA-Z\d]{8,}\S*$/'),
                ],
                'invalid_message' =>'Le mot de passe et la confirmation doivent être identique.',
                'label' => 'Votre mot de passe',
                'required'=>true,
                'first_options'=>[
                    'label'=>'Votre  mot de passe doit contenir au minimum 8 caractères :
                                avec au moins une lettre minuscule et une lettre majuscule, un caractère spécial et un chiffre',
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre mot de passe'
                    ]
                ],
                'second_options'=>[
                    'label'=>'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de confirmer votre mot de passe'
                    ]

                ]

            ])

            ->add('submit',SubmitType::class,[
                'label'=>"S'inscrire"

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
