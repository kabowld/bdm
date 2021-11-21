<?php


namespace App\DataFixtures;


use App\Entity\Category;
use App\Entity\Rubrique;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Self_;

class RubriqueFixtures extends Fixture implements FixtureGroupInterface
{

    public function load(ObjectManager $manager)
    {
        $listes = self::getListeRubriques();
        foreach ($listes as $rub => $categories)
        {
            $rubrique = new Rubrique();
            $rubrique
                ->setTitle($rub);
            $manager->persist($rubrique);

            foreach ($categories as $categorie) {
                $category = new Category();
                $category
                    ->setTitle($categorie)
                    ->setRubrique($rubrique);
                $manager->persist($category);
            }
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['rubriques'];
    }

    private static function getListeRubriques(): array
    {
        return
            [
                'Emploi et insertion sociale' => [
                    'Offres d\'emploi',
                    'Offres d\'emploi Cadres',
                    'Formations Professionnelles'
                ],
                'Vacances et famille' => [
                    'Locations studio/ appartement/ maisons',
                    'Hôtels'
                ],
                'Style et mode' => [
                    'Vêtements',
                    'Chaussures',
                    'Autres accessoires',
                    'Montres & Bijoux',
                    'Vêtements et accessoires bébé',
                    'Luxe'
                ],
                'Maison et équipement' => [
                    'Ameublement',
                    'Électroménager',
                    'Décoration intérieure/ extérieure',
                    'Linge de maison',
                    'Bricolage',
                    'Jardinage'
                ],
                'Véhicules et engins électriques' => [
                    'Voitures',
                    'Motos',
                    'Utilitaires',
                    'Camions',
                    'Équipement auto',
                    'Équipement moto'
                ],
                'Culture et loisirs' => [
                    'DVD - Films',
                    'CD - Musique',
                    'Livres',
                    'Vélos',
                    'Sports & Hobbies',
                    'Instruments de musique',
                    'Jeux & Jouets',
                    'Boissons'
                ],
                'Multimédia et services informatiques' => [
                    'Informatique',
                    'Consoles & Jeux vidéo',
                    'Image & Son',
                    'Téléphonie'
                ],
                'Equipement professionnel' => [
                    'Matériel agricole',
                    'Transport - Manutention',
                    'BTP - Chantier gros-oeuvre',
                    'Équipements industriels',
                    'Fournitures de bureau',
                    'Commerces & Marchés',
                    'Matériel médical'
                ],
                'Immobilier' => [
                    'Ventes immobilières',
                    'Immobilier Neuf',
                    'Locations immobilières',
                    'Bureaux & Commerces'
                ],
                'Animaux' => ['Animaux'],
                'Education' => ['Cours particuliers'],
                'Services et distribution' => [
                    'Prestations de services',
                    'Billetterie',
                    'Évènements',
                    'Covoiturage'
                ],
                'Divers' => ['Autres']
        ];
    }

}
