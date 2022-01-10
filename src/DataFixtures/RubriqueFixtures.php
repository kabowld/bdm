<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\FilePicture;
use App\Entity\Rubrique;
use App\Helper\FileUploaderHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\SluggerInterface;

class RubriqueFixtures extends Fixture implements FixtureGroupInterface
{
    private $fileUploaderHelper;
    private $slugger;

    /**
     * @param FileUploaderHelper $fileUploaderHelper
     * @param SluggerInterface $sluggerInterface
     */
    public function __construct(FileUploaderHelper $fileUploaderHelper, SluggerInterface $sluggerInterface)
    {
        $this->fileUploaderHelper = $fileUploaderHelper;
        $this->slugger = $sluggerInterface;
    }

    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $listes = self::getListeRubriques();
        foreach ($listes as $rub => $categories)
        {
            // FilePicture
            $file = $this->getFile($rub);
            $fileName = $this->fileUploaderHelper->upload($file);
            $filePicture = $this->getFilePicture($file, $fileName);
            $manager->persist($filePicture);

            // Rubrique
            $rubrique = $this->getRubrique($rub, $this->slugger->slug(strtolower($rub)), $filePicture);
            $manager->persist($rubrique);

            // Category
            foreach ($categories as $categorie) {
                $category = $this->getCategory($categorie, $rubrique, $this->slugger->slug(strtolower($categorie)));
                $manager->persist($category);
            }
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['rubriques'];
    }

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
     * @param string      $title
     * @param string      $slug
     * @param FilePicture $filePicture
     *
     * @return Rubrique
     */
    private function getRubrique(string $title, string $slug, FilePicture $filePicture): Rubrique
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
     * @param string $title
     *
     * @return File
     */
    private function getFile(string $title): File
    {
        return new File(
            FileUploaderHelper::relativeRubriquePath().
            DIRECTORY_SEPARATOR.
            $this->slugger->slug(strtolower($title)).FileUploaderHelper::EXT_PNG
        );
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
