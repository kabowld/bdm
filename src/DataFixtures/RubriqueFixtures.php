<?php

namespace App\DataFixtures;

use App\Helper\FileUploaderHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\SluggerInterface;

class RubriqueFixtures extends Fixture implements FixtureGroupInterface
{
    use RubriqueFixtureTrait;

    public const EXT_PNG = '.png';
    private FileUploaderHelper $fileUploaderHelper;
    private SluggerInterface $slugger;
    private string $rubriqueDirectory;
    private string $imagesDirectory;

    /**
     * @param FileUploaderHelper $fileUploaderHelper
     * @param SluggerInterface   $sluggerInterface
     * @param string             $rubriqueDirectory
     * @param string             $imagesDirectory
     */
    public function __construct(
        FileUploaderHelper $fileUploaderHelper,
        SluggerInterface $sluggerInterface,
        string $rubriqueDirectory,
        string $imagesDirectory)
    {
        $this->fileUploaderHelper = $fileUploaderHelper;
        $this->slugger = $sluggerInterface;
        $this->rubriqueDirectory = $rubriqueDirectory;
        $this->imagesDirectory = $imagesDirectory;
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
            $upload = $this->fileUploaderHelper->upload($this->relativePath($file), $this->getTargetRubriqueFile($file));
            if ($upload) {
                $filePicture = $this->getFilePicture($file, $this->fileUploaderHelper->getTargetFile($file));
            } else {
                $noFile = new File($this->imagesDirectory.DIRECTORY_SEPARATOR.'no-image.jpg');
                $filePicture = $this->getFilePicture($noFile, $this->fileUploaderHelper->getTargetFile($noFile));
            }
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


    /**
     * Return the original path with file
     *
     * @param File $file
     *
     * @return string
     */
    private function relativePath(File $file): string
    {
        $originalFilename = $this->fileUploaderHelper->getOriginalFileName($file);

        return $this->directoryRelativePath().DIRECTORY_SEPARATOR.$originalFilename.self::EXT_PNG;
    }

    /**
     * Return the target path
     *
     * @param File $file
     *
     * @return string
     */
    private function getTargetRubriqueFile(File $file): string
    {
        return $this->rubriqueDirectory.DIRECTORY_SEPARATOR.$this->fileUploaderHelper->getTargetFile($file);
    }

    /**
     * @return string
     */
    private function directoryRelativePath(): string
    {
        return dirname(__DIR__).DIRECTORY_SEPARATOR.'DataFixtures'.DIRECTORY_SEPARATOR.'Files';
    }

    /**
     * @param string $title
     *
     * @return File
     */
    private function getFile(string $title): File
    {
        return new File(
            $this->directoryRelativePath().
            DIRECTORY_SEPARATOR.
            $this->slugger->slug(strtolower($title)).self::EXT_PNG
        );
    }
}
