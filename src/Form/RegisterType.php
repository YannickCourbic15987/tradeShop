<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Votre email :',
                'attr' => [
                    "class" => "form-control mb-3",

                ],

            ])
            ->add('username', TextType::class, [
                'required' => true,
                'label' => 'Votre pseudo : ',
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('firstname', TextType::class, [
                'required' => true,
                'label' => 'Votre prénom :',
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('lastname', TextType::class, [
                'required' => true,
                'label' => 'Votre nom de famille :',
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'label' => 'Votre mot de passe :',
                'attr' => [
                    'class' => 'form-control mb-3 '
                ]
            ])

            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Confirmation de votre mot de passe :',
                'attr' => [
                    'class' => 'form-control mb-4'
                ]
            ])
            ->add('enregistrer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mb-2'
                ]
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
