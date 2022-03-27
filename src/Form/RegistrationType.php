<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Rubrique;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'attr' => [
                    'placeholder' => 'Saisir votre adresse mail',
                    'class' => 'form-control'
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas !',
                'required' => true,
                'options' => ['attr' => ['class' => 'form-control']],
                'first_options'  => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Saisir votre mot de passe',
                        'class' => 'form-control'
                    ]],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Saisir à nouveau votre mot de passe'
                    ],
                    'label' => 'Confirmer votre mot de passe'],
            ])
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' =>  'Saisir votre nom'],
                'label'=> 'Nom',
            ])
            ->add('firstname', TextType::class, [
                'attr' => ['placeholder' =>  'Saisir votre prénom','class' => 'form-control'],
                'label'=> 'Prénom',
            ])
            ->add('societyName', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Saisir le nom de votre société',],
                'label' => 'Nom de société'
            ])
            ->add('adresse', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Saisir votre adresse'],
                'label' => 'Adresse',
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'label' => 'Ville',
                'choice_label' => 'slug',
                'placeholder' => 'Sélectionner votre ville',
                'attr' => ['class' => 'form-control select-field']
            ])
            ->add('tel', TelType::class, [
                'attr' => ['placeholder' => 'Saisir votre numéro de téléphone', 'class' => 'form-control'],
                'label' => 'Numéro de téléphone',
            ])
            ->add('civility', ChoiceType::class, [
                'choices' => User::listCivility(),
                'expanded' => true,
                'multiple' => false,
                'label' => false
            ])
            ->add('rubrique', EntityType::class, [
                'class' => Rubrique::class,
                'label' => 'Catégorie de votre activité',
                'choice_label' => 'title',
                'placeholder' => 'Sélectionner la catégorie de votre activité',
                'attr' => [ 'class' => 'form-control select-field']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Sauvegarder',
                'attr' => [
                    'class' => 'btn btn-log btn-thm2 register-btn'
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
