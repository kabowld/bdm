<?php
declare(strict_types=1);

namespace App\DataFixtures;


use App\Entity\CategoryState;
use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class StateFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $states = $this->getStates();

        $baseStars = 5;
        $fashionStars = 4;
        foreach ($states as $type => $values) {
            $category = $this->getCategoryState($type);
            $manager->persist($category);

            foreach ($values as $title => $description) {
                $state = $this->getState($title, $description, $category, ['normal' => $baseStars, 'style' => $fashionStars], $type);
                $manager->persist($state);

                if ($type === 'normal') {
                    $baseStars--;
                    continue;
                }

                $fashionStars--;
            }
        }

        $manager->flush();
    }

    /**
     * @param string $title
     *
     * @return CategoryState
     */
    private function getCategoryState(string $title): CategoryState
    {
        return (new CategoryState())->setTitle($title);
    }

    /**
     * @param string        $title
     * @param string        $description
     * @param CategoryState $categoryState
     * @param array         $stars
     * @param string        $type
     *
     * @return State
     */
    private function getState(string $title, string $description, CategoryState $categoryState, array $stars, string $type): State
    {
        $state = new State();
        $state
            ->setTitle($title)
            ->setDescription($description)
            ->setCategoryState($categoryState)
        ;

        if ($type === 'normal' && !empty($stars['normal'])) {
            $state->setStars($stars['normal']);
        }

        if ($type === 'style' && !empty($stars['style'])) {
            $state->setStars($stars['style']);
        }

        return $state;
    }

    /**
     * @return \string[][]
     */
    private function getStates(): array
    {
        return [
            'normal' => [
                'Neuf' => "Bien non-utilis??, non endommag??, complet, avec emballage non ouvert et notice(s)  d???utilisation. L'objet peut ??tre propos?? sans son emballage d'origine, ou dans l'emballage  d'origine non scell??",
                'Tr??s bon ??tat' => "Bien peu utilis?? ; il ne pr??sente aucun dommage, aucune ??raflure, aucune rayure. Il est complet et en parfait ??tat de fonctionnement.",
                'Bon ??tat' => "Bien en bon ??tat de fonctionnement, pr??sentant des dommages minimes et comportant  quelques petits d??fauts (mentionn??s dans l???annonce et visibles sur les photos).",
                'Satisfaisant' => "Bien en ??tat de fonctionnement correct, comportant des d??fauts et signes d???usure  manifestes (mentionn??s dans l???annonce et visibles sur les photos).",
                'Ab??m??' => 'Bien non fonctionnel n??cessitant une r??paration ou un entretien pour restauration compl??te  ou r??cup??ration de pi??ces d??tach??es.',
            ],
            'style' => [
                'Neuf avec ??tiquette' => "Article neuf, inutilis?? et non port?? dans son emballage d'origine  et/ou avec les ??tiquettes d'origine attach??es.",
                'Neuf sans ??tiquette' => "Article enti??rement neuf, inutilis?? et non port?? , vendu sans  l'emballage d'origine ou dont des ??l??ments d'emballage d'origine sont manquants. Il se  peut que les ??tiquettes d'origine ne soient pas attach??es.",
                'Neuf avec des d??fauts' => "Article enti??rement neuf, inutilis?? et non port??. Il peut pr??senter  des d??fauts cosm??tiques survenus lors de la fabrication ou de la livraison, notamment  des variations de couleur naturelles, des ??raflures, des coupures, des entailles, des fils  
pendants ou des boutons manquants. Le v??tement peut pr??senter des ??tiquettes de  taille irr??guli??re ou incorrectes. Les ??l??ments d'emballage d'origine peuvent ??tre  manquants. Les objets neufs pr??sentant des d??fauts de fabrication et/ou des irr??gularit??s  peuvent entrer dans cette cat??gorie. Il se peut que les ??tiquettes d'origine ne soient pas  attach??es.",
                'Occasion' => "Article qui a ??t?? utilis?? ou port?? pr??sentant peut-??tre quelques petits d??fauts  ou traces d???usure (mentionn??s dans l???annonce et visibles sur les photos)."
            ]
        ];
    }

    /**
     * @return array
     */
    public static function getGroups(): array
    {
        return ['state'];
    }
}
