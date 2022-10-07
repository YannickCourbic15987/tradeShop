<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordResetChangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control
                    mt-2 mb-4'
                ],
                'label' => 'nouveau mot de passe : ',
                'required' => true
            ])

            ->add('newPasswordConfirm', PasswordType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control mt-2 mb-4'
                ],
                'label' => 'nouveau mot de passe à confirmer :',
                'required' => true,
            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
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
