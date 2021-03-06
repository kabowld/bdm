<?php

namespace App\DataFixtures;

use App\Helper\FileUploaderHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\SluggerInterface;

class RubriqueFixtures extends Fixture implements FixtureGroupInterface
{
    use RubriqueFixtureTrait;

    public const EXT_PNG = '.png';
    private const MESS_FILE_ERROR = 'Error fires creating file object: %s';

    private FileUploaderHelper $fileUploaderHelper;
    private SluggerInterface $slugger;
    private string $rubriqueDirectory;
    private string $imagesDirectory;
    private LoggerInterface $logger;

    /**
     * @param FileUploaderHelper $fileUploaderHelper
     * @param SluggerInterface   $sluggerInterface
     * @param string             $rubriqueDirectory
     * @param string             $imagesDirectory
     * @param LoggerInterface    $logger
     */
    public function __construct(
        FileUploaderHelper $fileUploaderHelper,
        SluggerInterface $sluggerInterface,
        string $rubriqueDirectory,
        string $imagesDirectory,
        LoggerInterface $logger
    )
    {
        $this->fileUploaderHelper = $fileUploaderHelper;
        $this->slugger = $sluggerInterface;
        $this->rubriqueDirectory = $rubriqueDirectory;
        $this->imagesDirectory = $imagesDirectory;
        $this->logger = $logger;
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
            $filePicture = null;
            // FilePicture
            $file = $this->getFile($rub);
            if (!is_null($file)) {
                $targetFile = $this->fileUploaderHelper->getTargetFile($file);
                $filePicture = $this->getFilePicture($file, $targetFile);
                $manager->persist($filePicture);
                $this->fileUploaderHelper->upload($this->relativePath($file), $this->getTargetRubriqueFile($file, $targetFile));
            }

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
     * @param File   $file
     * @param string $targetFile
     *
     * @return string
     */
    private function getTargetRubriqueFile(File $file, string $targetFile): string
    {
        return $this->rubriqueDirectory.DIRECTORY_SEPARATOR.$targetFile;
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
     * @return File|null
     */
    private function getFile(string $title): ?File
    {
        try {
            return new File(
                $this->directoryRelativePath().
                DIRECTORY_SEPARATOR.
                $this->slugger->slug(strtolower($title)).self::EXT_PNG
            );
        } catch (FileNotFoundException $e) {
            $this->logger->warning(sprintf(self::MESS_FILE_ERROR, $e->getMessage()));
        }

        return null;
    }

    /**
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['rubriques'];
    }

}
