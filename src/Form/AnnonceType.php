<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\City;
use App\Entity\Commune;
use App\Entity\Region;
use App\Entity\State;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceType extends AbstractType
{
    private const CAPITALE = 'abidjan';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'label' => 'Titre de l\'annonce'
                ]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Offre' => 'offre',
                    'Demande' => 'demande',
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 8,
                    'cols' => 5
                ]
            ])
            ->add('price', MoneyType::class, ['attr' => ['class' => 'form-control']])
            ->add('location', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('postalCode', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('state', EntityType::class, [
                'class' => State::class,
                'placeholder' => 'Sélectionner un état',
                'choice_label' => 'title',
                'attr' => ['class' => 'form-control']
            ])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'placeholder' => 'Sélectionner la région',
                'choice_label' => 'title',
                'attr' => ['class' => 'form-control select-field']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Sauvegarder',
                'attr' => ['class' => 'btn btn-log btn-thm2 register-btn']
            ])
        ;

        $builder->get('region')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) {
                $form = $event->getForm();
                $this->addCityField($form->getParent(), $form->getData());
                $this->addCommuneField($form->getParent(), null);
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $event->getForm()
                    ->add('city', EntityType::class, [
                        'class' => City::class,
                        'placeholder' => 'Selectionner la région',
                        'choice_label' => 'title',
                        'choices' => [],
                        'required' => true,
                        'attr' => ['class' => 'form-control', 'disabled' => 'disabled']
                    ])
                    ->add('commune', EntityType::class, [
                        'class' => Commune::class,
                        'placeholder' => 'Sélectionner la ville',
                        'choice_label' => 'title',
                        'required' => false,
                        'choices' => [],
                        'attr' => ['class' => 'form-control', 'disabled' => 'disabled']
                    ]);
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();
                /** @var Commune $commune */

                if ($commune = $data->getCommune()) {
                    $city = $commune->getCity();
                    $region = $city->getRegion();
                    $this->addCommuneField($form, $city);
                    $this->addCityField($form, $region);
                    $form->get('city')->setData($city);
                    $form->get('region')->setData($region);
                    $form->get('commune')->setData($commune);

                    return;
                }
                if ($city = $data->getCity()) {
                    /** @var City $city */
                    $region = $city->getRegion();
                    $this->addCommuneField($form, $city);
                    $this->addCityField($form, $region);
                    $form->get('city')->setData($city);
                    $form->get('region')->setData($region);

                    return;
                }
                $this->addCommuneField($form, null);
                $this->addCityField($form, null);
            }
        );
    }

    /**
     * @param FormInterface $form
     * @param Region|null $region
     */
    private function addCityField(FormInterface $form, ?Region $region)
    {
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'city',
            EntityType::class,
            null,
            [
                'class' => City::class,
                'placeholder' => $region ? 'Sélectionner la ville': 'Selectionner la région',
                'choice_label' => 'title',
                'choices' => $region ? $region->getCities(): [],
                'required' => true,
                'auto_initialize' => false,
                'attr' => ['class' => 'form-control']
            ]
        );
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->addCommuneField($form->getParent(), $form->getData());
            }
        );
        $form->add($builder->getForm());
    }

    /**
     * @param FormInterface $form
     * @param City|null $city
     */
    private function addCommuneField(FormInterface $form, ?City $city)
    {
        $form->add('commune', EntityType::class, [
            'class' => Commune::class,
            'placeholder' => $city ? 'Sélectionner la commune': 'Sélectionner la ville',
            'choice_label' => 'title',
            'required' => false,
            'choices' => $city ? $city->getCommunes() : [],
            'attr' => ['class' => 'form-control']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
