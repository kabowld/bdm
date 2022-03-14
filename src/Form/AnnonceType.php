<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\Pack;
use App\Entity\Rubrique;
use App\Entity\State;
use App\Validator\PriceAnnonceRulesValidator;
use App\Validator\StateAnnonceRulesValidator;
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

class AnnonceType extends AbstractType
{
    private const NORMAL_STATE = 'normal';
    private const FASHION_STATE = 'style';

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

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
                    'rows' => 15,
                    'cols' => 5,
                    'placeholder' => 'Saisir une description'
                ],
                'label' => 'Description de l\'annonce'
            ])
            ->add('location', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Saisir un emplacement'
                ],
                'label' => 'Adresse précise',
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
                'mapped' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.title', 'ASC');
                }
            ])
            ->add('lat', HiddenType::class)
            ->add('lng', HiddenType::class)
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
                'choice_label' => 'title',
                'placeholder' => 'Sélectionner un pack',
                'attr' => ['class' => 'form-control'],
                'label' => false,
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Sauvegarder',
                'attr' => ['class' => 'btn btn-log btn-thm2 register-btn']
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
        $builder->addEventListener(FormEvents::POST_SET_DATA,[$this, 'onPostSetData']);

        $builder->get('rubrique')->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmit']);
    }


    /**
     * @param FormEvent $event
     *
     * @return void
     */
    public function onPreSetData(FormEvent $event): void
    {
        $this->createFormCategory($event->getForm());
        $this->createFormState($event->getForm());
        $this->editPriceForm($event->getForm());
    }

    /**
     * @param FormEvent $event
     *
     * @return void
     */
    public function onPostSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $data = $event->getData();
        $category = $data->getCategory();

        if ($category) {
            $rubrique = $category->getRubrique();

            $form->get('category')->setData($category);
            $form->get('rubrique')->setData($rubrique);
            $this->createFormCategory($event->getForm(), $rubrique);
            $this->createFormState($event->getForm(), $rubrique);
            $this->editPriceForm($event->getForm(), $rubrique);
        }

        $form->get('state')->setData($data->getState());
    }

    /**
     * @param FormEvent $event
     *
     * @return void
     */
    public function onPostSubmit(FormEvent $event): void
    {
        $this->createFormCategory($event->getForm()->getParent(), $event->getForm()->getData());
        $this->createFormState($event->getForm()->getParent(), $event->getForm()->getData());
        $this->editPriceForm($event->getForm()->getParent(), $event->getForm()->getData());

    }

    /**
     * @param FormInterface $form
     * @param Rubrique|null $rubrique
     *
     * @return void
     */
    private function createFormCategory(FormInterface $form, Rubrique $rubrique = null): void
    {
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
    }

    /**
     * @param FormInterface $form
     * @param Rubrique|null $rubrique
     *
     * @return void
     */
    private function createFormState(FormInterface $form, Rubrique $rubrique = null): void
    {
        $required = true;
        $placeholder = 'Sélectionner un état';
        if (!is_null($rubrique) && $rubrique->getTitle() === 'Style et mode') {
            $states = $this->em->getRepository(State::class)->getStateArrayByCategoryType(self::FASHION_STATE);
        }
        elseif (!is_null($rubrique) && in_array($rubrique->getslug(), StateAnnonceRulesValidator::BANNED_RUBRIQUES)) {
            $states = [];
            $required = false;
            $placeholder = 'Aucun état pour cette rubrique';
        }
        elseif ($rubrique === null) {
            $states = $this->em->getRepository(State::class)->findAll();
            $required = false;
            $placeholder = 'Sélectionner la rubrique';
        } else {
            $states = $this->em->getRepository(State::class)->getStateArrayByCategoryType(self::NORMAL_STATE);

        }

        $form->add('state', EntityType::class, [
            'class' => State::class,
            'choices' => $states,
            'placeholder' => $placeholder,
            'label' => 'Etat',
            'choice_label' => function (State $state) {
                return $state->getTitle();
            },
            'attr' => ['class' => 'form-control'],
            'required' => $required
        ]);
    }
    /**
     * @param FormInterface $form
     * @param Rubrique|null $rubrique
     *
     * @return void
     */
    private function editPriceForm(FormInterface $form, Rubrique $rubrique = null): void
    {
        if (!is_null($rubrique) && $rubrique->getSlug() === PriceAnnonceRulesValidator::RUBRIQUE_SLUG) {
            $form->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => 'disabled',
                    'placeholder' => 'Aucun prix à saisir pour le choix de cette rubrique'
                ],
                'currency' => false,
                'label' => 'Aucun prix à saisir pour cette rubrique (en CFA)'
            ]);
        } else {
            $form->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Saisir le prix de l\'annnonce'
                ],
                'currency' => false,
                'label' => 'Définir un prix (en CFA)'
            ]);
        }
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }

}
