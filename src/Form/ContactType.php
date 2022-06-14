<?php

namespace App\Form;

use App\Entity\Contact;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
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
            ->add('content',TextareaType::class, [
                'label' =>'Votre message',
                'constraints'=>[
                    new NotBlank()
                ],
                'attr' => [
                    'placeholder' => 'En quoi pouvons-nous vous aider ?'
                ]
            ])
            ->add('submit',SubmitType::class,[
                'label'=>"Envoyer"

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
