<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Profil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProfilType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pictureProfil', FileType::class, [
                'attr' => [
                    'class' => 'input-file ',
                    // 'id' => 'file_upload_input',
                ],
                'mapped' => false,
                'label' => 'Choisir son image de profil',
                'label_attr' => [
                    'class' => 'label-file',

                ],
                'required' => false,
                'constraints' => [
                    new File(
                        [

                            'mimeTypes' => [
                                'image/jpg',
                                'iamge/gif',
                                'image/jpeg',
                                'image/svg',
                                'image/png',
                            ],
                            'mimeTypesMessage' => 's\'il vous plait choissisez un format d\'image valide ',
                        ]
                    )
                ]

            ])
            ->add('phone', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Numéro de téléphone:',
                'label_attr' => [
                    'class' => 'mt-2'
                ]
            ])
            ->add('id_User', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
                'label_attr' => [
                    'class' => 'd-none'
                ],
                'attr' => [
                    'class' => 'd-none'
                ],
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
            'data_class' => Profil::class,
        ]);
    }
}
