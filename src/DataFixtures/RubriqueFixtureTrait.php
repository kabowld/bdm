<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\FilePicture;
use App\Entity\Rubrique;
use Symfony\Component\HttpFoundation\File\File;

trait RubriqueFixtureTrait
{
    /**
     * @param File   $file
     * @param string $filename
     *
     * @return FilePicture
     */
    private function getFilePicture(File $file, string $filename): FilePicture
    {
        return (new FilePicture())
            ->setFile($file)
            ->setFileName($filename)
            ;
    }

    /**
     * @param string           $title
     * @param string           $slug
     * @param FilePicture|null $filePicture
     *
     * @return Rubrique
     */
    private function getRubrique(string $title, string $slug, ?FilePicture $filePicture): Rubrique
    {
        return (new Rubrique())
            ->setTitle($title)
            ->setSlug($slug)
            ->setImage($filePicture)
            ;
    }

    /**
     * @param string   $title
     * @param Rubrique $rubrique
     * @param string   $slug
     *
     * @return Category
     */
    private function getCategory(string $title, Rubrique $rubrique, string $slug): Category
    {
        return (new Category())
            ->setTitle($title)
            ->setRubrique($rubrique)
            ->setSlug($slug)
            ;
    }

    /**
     * @return \string[][]
     */
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
