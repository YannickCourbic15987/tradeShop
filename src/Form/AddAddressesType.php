<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Addresses;
use App\Entity\CategAdress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddAddressesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adresse', SearchType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Votre adresse :',

            ])
            ->add('number_of_street', NumberType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'N° de Rue :',
            ])
            ->add('name_of_street', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nom de la rue :',
            ])
            ->add('typeOfWay', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Type de Voie :',
            ])
            ->add('PostalCode', NumberType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Code Postal :',
                'label_attr' => [
                    'class' => 'form-label '
                ]
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Ville :',
            ])
            ->add('Department', TextType::class, [
                'attr' => [
                    'class' => 'form-control d-none'
                ],
                'label' => 'Département :',
                'label_attr' => [
                    'class' => 'd-none'
                ]
            ])
            ->add('Region', TextType::class, [
                'attr' => [
                    'class' => 'form-control d-none'
                ],
                'label' => 'Région :',
                'label_attr' => [
                    'class' => 'd-none'
                ]
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
                'label_attr' => [
                    'class' => 'd-none'
                ],
                'attr' => [
                    'class' => 'd-none'
                ]
            ])
            ->add('categAdress', EntityType::class, [
                'class' => CategAdress::class,
                'choice_label' => 'id',
                'label_attr' => [
                    'class' => 'd-none'
                ],
                'attr' => [
                    'class' => 'd-none'
                ]

            ])
            ->add('Enregistrer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-success mt-3'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Addresses::class,
            // 'data_class' => User::class,

        ]);
    }
}
