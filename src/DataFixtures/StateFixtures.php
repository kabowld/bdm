<?php


namespace App\DataFixtures;


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
            'Neuf' => "Bien non-utilisé, non endommagé, complet, avec emballage non ouvert et notice(s)  d’utilisation. L'objet peut être proposé sans son emballage d'origine, ou dans l'emballage  d'origine non scellé",
            'Très bon état' => "Bien peu utilisé ; il ne présente aucun dommage, aucune éraflure, aucune rayure. Il est complet et en parfait état de fonctionnement.",
            'Bon état' => "Bien en bon état de fonctionnement, présentant des dommages minimes et comportant  quelques petits défauts (mentionnés dans l’annonce et visibles sur les photos).",
            'Satisfaisant' => "Bien en état de fonctionnement correct, comportant des défauts et signes d’usure  manifestes (mentionnés dans l’annonce et visibles sur les photos).",
            'Abîmé' => 'Bien non fonctionnel nécessitant une réparation ou un entretien pour restauration complète  ou récupération de pièces détachées.',
        ];

        foreach($states as $title => $description) {
            $state = new State();
            $state
                ->setTitle($title)
                ->setDescription($description)
            ;
            $manager->persist($state);
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
