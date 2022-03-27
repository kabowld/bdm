<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Rubrique;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilProType extends AbstractType
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
                'required' => false
            ])
            ->add('passwordVerify', PasswordType::class, [
                'mapped' => false,
                'attr' => ['class' => 'form-control', 'placeholder' =>  'Saisir votre mot de passe'],
                'label'=> 'Confirmer votre mot de passe',
                'required' => true
            ])
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' =>  'Saisir votre nom'],
                'label'=> 'Nom',
            ])
            ->add('firstname', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' =>  'Saisir votre prénom'],
                'label'=> 'Prénom',
            ])
            ->add('adresse', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' =>  'Saisir votre adresse'],
                'label'=> 'Adresse',
                'required' => false
            ])
            ->add('tel', TelType::class, [
                'attr' => ['placeholder' => 'Saisir votre numéro de téléphone', 'class' => 'form-control'],
                'label' => 'Numéro de téléphone',
                'required' => false
            ])
            ->add('civility', ChoiceType::class, [
                'choices' => User::listCivility(),
                'expanded' => true,
                'multiple' => false,
                'label' => false,
            ])
            ->add('societyName', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Saisir le nom de votre société',],
                'label' => 'Nom de société'
            ])
            ->add('rubrique', EntityType::class, [
                'class' => Rubrique::class,
                'label' => 'Catégorie de votre activité',
                'choice_label' => 'title',
                'placeholder' => 'Sélectionner la catégorie de votre activité',
                'attr' => [ 'class' => 'form-control select-field']
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'label' => 'Ville',
                'choice_label' => 'slug',
                'placeholder' => 'Sélectionner votre ville',
                'attr' => ['class' => 'form-control select-field']
            ])
            ->add('avatarFile', FileType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer les modifications',
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
