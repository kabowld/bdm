<?php

namespace App\Helper;

use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\SluggerInterface;

final class FileUploaderHelper
{
    use LoggerAwareTrait;

    public const EXT_PNG = '.png';
    private string $rubriqueDirectory;
    private SluggerInterface $slugger;

    /**
     * @return string
     */
    public static function relativeRubriquePath(): string
    {
        return dirname(__DIR__).DIRECTORY_SEPARATOR.'DataFixtures'.DIRECTORY_SEPARATOR.'Files';
    }

    /**
     * @param string $rubriqueDirectory
     * @param SluggerInterface $slugger
     */
    public function __construct(string $rubriqueDirectory, SluggerInterface $slugger)
    {
        $this->rubriqueDirectory = $rubriqueDirectory;
        $this->slugger = $slugger;
    }

    /**
     * @param File $file
     *
     * @return string
     */
    public function upload(File $file): string
    {
        $originalFilename = pathinfo($file->getFilename(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        $fileSystem = new Filesystem();
        $fileSystem->copy(
            self::relativeRubriquePath().DIRECTORY_SEPARATOR.$originalFilename.self::EXT_PNG,
            $this->getTargetDirectory().DIRECTORY_SEPARATOR.$fileName
        );

        return $fileName;
    }

    /**
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->rubriqueDirectory;
    }
}
