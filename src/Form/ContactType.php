<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['attr' => [
                'class' => 'form-control',
                'placeholder' => 'Votre nom et prénom *'
            ]])
            ->add('email', TextType::class, ['attr' => [
                'class' => 'form-control',
                'placeholder' => 'Votre email *'
            ]])
            ->add('subject', TextType::class, ['attr' => [
                'class' => 'form-control',
                'placeholder' => 'Votre sujet *'
            ]])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'rows' => 8,
                    'placeholder' => 'Votre message *',
                    'class' => 'form-control required'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => ['class' => 'btn-contact-send']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
