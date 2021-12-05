<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilParticularType extends AbstractType
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
                'attr' => ['class' => 'form-control', 'placeholder' =>  'Saisir votre mot de de passe'],
                'label'=> 'Confirmer votre mot de passe',
                'required' => true
            ])
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' =>  'Saisir votre nom'],
                'label'=> 'Nom',
                'required' => false
            ])
            ->add('firstname', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' =>  'Saisir votre prénom'],
                'label'=> 'Prénom',
                'required' => false
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
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer les modifications',
                'attr' => [
                    'class' => 'btn btn-log btn-thm2 register-btn'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
