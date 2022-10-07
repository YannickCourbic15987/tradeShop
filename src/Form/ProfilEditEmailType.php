<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilEditEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldEmail', EmailType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control mt-2 mb-3'
                ],
                'label' => 'Votre ancien email : ',
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => "votre nouveau email : ",
                "label_attr" => [
                    'class' => 'mb-1'
                ]
            ])
            ->add('emailConfirm', EmailType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'label' => 'Confirmation de votre nouveau email : ',
                'required' => true,
                'label_attr' => [
                    'class' => 'mb-1'
                ],
            ])
            ->add('valider', SubmitType::class, [
                "attr" => [
                    'class' => 'btn btn-outline-warning'
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
