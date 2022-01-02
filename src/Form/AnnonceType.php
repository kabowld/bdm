<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\Pack;
use App\Entity\Rubrique;
use App\Entity\State;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Doctrine\ORM\QueryBuilder;

class AnnonceType extends AbstractType
{
    private const NORMAL_STATE = 'normal';
    private const FASHION_STATE = 'style';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Saisissez le titre de l\'annonce'
                ],
                'label' => 'Titre de l\'annonce',
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Offre' => 'offre',
                    'Demande' => 'demande',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Type de la transaction',
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 8,
                    'cols' => 5,
                    'placeholder' => 'Saisir une description'
                ],
                'label' => 'Description de l\'annonce'
            ])
            ->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Saisir le prix de l\'annnonce'
                ],
                'currency' => false,
                'label' => 'Définir un prix'
            ])
            ->add('location', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Saisir un emplacement'
                ],
                'mapped' => false,
                'label' => 'Adresse',
                'help' => 'Lieu de l\'emplacement où vous retrouver'
            ])
            ->add('postalCode', HiddenType::class)
            ->add('city', EntityType::class, [
                'class' => City::class,
                'placeholder' => 'Sélectionner la ville',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.title', 'ASC');
                },
                'choice_label' => 'title',
                'label' => 'Votre ville',
                'attr' => ['class' => 'form-control select-field', 'data-live-search' => true ]
            ])
            ->add('rubrique', EntityType::class, [
                'class' => Rubrique::class,
                'choice_label' => 'title',
                'label' => 'Rubrique',
                'placeholder' => 'Sélectionner la rubrique',
                'attr' => ['class' => 'form-control'],
                'mapped' => false
            ])
            ->add('lat', HiddenType::class)
            ->add('lng', HiddenType::class)
            ->add('state', EntityType::class, [
                'class' => State::class,
                'query_builder' => function (StateRepository $er) {
                    return $er->getStateByCategoryType(self::NORMAL_STATE);
                },
                'placeholder' => 'Sélectionner un état',
                'label' => 'Etat',
                'choice_label' => function (State $state) {
                    return $state->getTitle();
                },
                'attr' => ['class' => 'form-control']
            ])
            ->add('pictureOneFile', FileType::class, [
                'required' => false,
            ])
            ->add('pictureTwoFile', FileType::class, [
                'required' => false,
             ])
            ->add('pictureThreeFile', FileType::class, [
                'required' => false,
            ])
            ->add('pictureFourFile', FileType::class, [
                'required' => false,
            ])
            ->add('pictureFiveFile', FileType::class, [
                'required' => false,
            ])
            ->add('pictureSixFile', FileType::class, [
                'required' => false,
            ])
            ->add('pictureSevenFile', FileType::class, [
                'required' => false,
            ])
            ->add('pictureHeightFile', FileType::class, [
                'required' => false,
            ])
            ->add('pack', EntityType::class, [
                'class' => Pack::class,
                'choice_label' => function (Pack $pack) {
                    return sprintf('%s Fcfa  %s  %s Jours', $pack->getPrice(), $pack->getPriceByDay(), $pack->getDays());
                },
                'multiple' => false,
                'expanded' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.id','ASC');
                },
                'label' => false,
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Sauvegarder',
                'attr' => ['class' => 'btn btn-log btn-thm2 register-btn']
            ])
        ;

        $formModifier = function (FormInterface $form, Rubrique $rubrique = null) {
            $categories = null === $rubrique ? [] : $rubrique->getCategories();
            $placeholder = null === $rubrique ? 'Sélectionner la rubrique': 'Sélectionner la catégorie';

            $form->add('category', EntityType::class, [
                'class' => Category::class,
                'choices' => $categories,
                'choice_label' => 'title',
                'label' => 'Catégorie',
                'placeholder' => $placeholder,
                'attr' => ['class' => 'form-control'],
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();

                $formModifier($event->getForm(), $data->getCategory());
            }
        );

        $builder->get('rubrique')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $rubrique = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $rubrique);
            }
        );
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                /** @var $data Annonce */
                if ($category = $data->getCategory()) {
                    $form->get('category')->setData($category);
                    $form->get('rubrique')->setData($category->getRubrique());
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }

}
