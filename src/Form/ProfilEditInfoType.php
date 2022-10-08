<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProfilEditInfoType extends AbstractType
{


    public function __construct(
        Security $security
    ) {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                "attr" => [
                    'class' => 'form-control mb-2',
                    'placeholder' => $this->security->getUser()->getUsername()
                ],
                'label' => 'votre pseudo : ',
                'required' => false
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder' => $this->security->getUser()->getLastname()
                ],
                'label' => 'votre prénom : ',
                'required' => false
            ])
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => $this->security->getUser()->getFirstname()
                ],
                'label' => 'votre nom de famille : ',
                'required' => false
            ])
            ->add('Modifier', SubmitType::class, [
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
