<?php

namespace App\Form;

use App\Entity\AnnonceSearch;
use App\Entity\Category;
use App\Entity\City;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', TextType::class, [
                'attr' => ['class' => 'form-control input-search', 'placeholder' => 'Que recherchez - vous ?'],
                'label' => false,
                'required' => false
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'placeholder' => 'Toutes les catégories',
                'attr' => ['class' => 'form-control select-field select-category'],
                'label' => false,
                'required' => false,
                'choice_value' => 'slug'
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Offre' => 'offre',
                    'Demande' => 'demande',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => false,
                'required' => false
            ])
            ->add('city', EntityType::class, [
                'attr' => ['class' => 'form-control select-field select-city'],
                'class' => City::class,
                'placeholder' => 'Toute la  Côte d\'Ivoire',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.title', 'ASC');
                },
                'choice_label' => 'title',
                'required' => false,
                'choice_value' => 'slug'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AnnonceSearch::class,
            'method'     => 'GET'
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
