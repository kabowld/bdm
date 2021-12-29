<?php


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
        $states = [
            'normal' => [
                'Neuf' => "Bien non-utilisé, non endommagé, complet, avec emballage non ouvert et notice(s)  d’utilisation. L'objet peut être proposé sans son emballage d'origine, ou dans l'emballage  d'origine non scellé",
                'Très bon état' => "Bien peu utilisé ; il ne présente aucun dommage, aucune éraflure, aucune rayure. Il est complet et en parfait état de fonctionnement.",
                'Bon état' => "Bien en bon état de fonctionnement, présentant des dommages minimes et comportant  quelques petits défauts (mentionnés dans l’annonce et visibles sur les photos).",
                'Satisfaisant' => "Bien en état de fonctionnement correct, comportant des défauts et signes d’usure  manifestes (mentionnés dans l’annonce et visibles sur les photos).",
                'Abîmé' => 'Bien non fonctionnel nécessitant une réparation ou un entretien pour restauration complète  ou récupération de pièces détachées.',
            ],
            'style' => [
                'Neuf avec étiquette' => "Article neuf, inutilisé et non porté dans son emballage d'origine  et/ou avec les étiquettes d'origine attachées.",
                'Neuf sans étiquette' => "Article entièrement neuf, inutilisé et non porté , vendu sans  l'emballage d'origine ou dont des éléments d'emballage d'origine sont manquants. Il se  peut que les étiquettes d'origine ne soient pas attachées.",
                'Neuf avec des défauts' => "Article entièrement neuf, inutilisé et non porté. Il peut présenter  des défauts cosmétiques survenus lors de la fabrication ou de la livraison, notamment  des variations de couleur naturelles, des éraflures, des coupures, des entailles, des fils  
pendants ou des boutons manquants. Le vêtement peut présenter des étiquettes de  taille irrégulière ou incorrectes. Les éléments d'emballage d'origine peuvent être  manquants. Les objets neufs présentant des défauts de fabrication et/ou des irrégularités  peuvent entrer dans cette catégorie. Il se peut que les étiquettes d'origine ne soient pas  attachées.",
                'Occasion' => "Article qui a été utilisé ou porté présentant peut-être quelques petits défauts  ou traces d’usure (mentionnés dans l’annonce et visibles sur les photos)."
            ]
        ];




        foreach($states as $type => $values) {
            $category = new CategoryState();
            $category->setTitle($type);
            $manager->persist($category);
            foreach ($values as $title => $item) {
                $state = new State();
                $state
                    ->setTitle($title)
                    ->setDescription($item)
                    ->setCategoryState($category)
                ;
                $manager->persist($state);
            }
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public static function getGroups(): array
    {
        return ['state'];
    }
}
