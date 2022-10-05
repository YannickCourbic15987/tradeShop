<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilEditPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control mt-2 mb-3'
                ],
                'label' => 'Votre ancien mot de passe : ',
                'required' => true
            ])
            ->add('password', PasswordType::class, [
                "attr" => [
                    'class' => 'form-control mb-3'
                ],
                "label" => "Votre nouveau mot de passe :",
                "label_attr" => [
                    'class' => 'mb-1'
                ],

                'required' => true
            ])
            ->add('passwordConfirm', PasswordType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Confirmation de votre nouveau mot de passe : ',
                'required' => true,
                "label_attr" => [
                    'class' => 'mb-1'
                ],

            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-success'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
