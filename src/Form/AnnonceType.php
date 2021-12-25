<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\Rubrique;
use App\Entity\State;
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
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceType extends AbstractType
{

    private $em;

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
                'label' => 'Choisir le type de l\'annonce',
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
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'label' => 'Catégorie',
                'placeholder' => 'Sélectionner la catégorie',
                'attr' => ['class' => 'form-control select-field', 'data-live-search' => true ],
            ])
            ->add('lat', HiddenType::class)
            ->add('lng', HiddenType::class)
            ->add('state', EntityType::class, [
                'class' => State::class,
                'placeholder' => 'Sélectionner un état',
                'label' => 'Etat',
                'choice_label' => 'title',
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
            ->add('save', SubmitType::class, [
                'label' => 'Sauvegarder',
                'attr' => ['class' => 'btn btn-log btn-thm2 register-btn']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }

    /**
     * @return array
     */
    private function getCategories(): array
    {
        $choices = [];
        $rubriques = $this->em->getRepository(Rubrique::class)->getAllRubriqueByOrderAsc();

        foreach ($rubriques as $rubrique) {
            /** @var Rubrique $rubrique */
            $choices[$rubrique->getTitle()] = $this->getCatsByIdRubrique($rubrique->getId());
        }

        return $choices;
    }

    /**
     * @param int $id
     *
     * @return array
     */
    private function getCatsByIdRubrique(int $id): array
    {
        return $this->em->getRepository(Category::class)->getCategoriesByRubrique($id);
    }

}
