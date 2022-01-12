<?php

namespace App\Helper;

use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\SluggerInterface;

final class FileUploaderHelper
{
    private SluggerInterface $slugger;
    private LoggerInterface $logger;

    /**
     * @param SluggerInterface $slugger
     * @param LoggerInterface  $logger
     */
    public function __construct(SluggerInterface $slugger, LoggerInterface $logger)
    {
        $this->slugger = $slugger;
        $this->logger = $logger;
    }

    /**
     * @param string $originFile
     * @param string $targetFile
     *
     * @return void
     */
    public function upload(string $originFile, string $targetFile): void
    {
        $fileSystem = new Filesystem();
        try {
            $fileSystem->copy($originFile, $targetFile);
        } catch (FileNotFoundException|IOException $e) {
            $this->logger->error(sprintf('Error file to copy : %s', $e->getMessage()));
        }
    }

    /**
     * @param File $file
     *
     * @return string
     */
    public function getTargetFile(File $file): string
    {
        $originalFilename = $this->getOriginalFileName($file);
        $safeFilename = $this->slugger->slug($originalFilename);

        return $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
    }

    /**
     * @param File $file
     *
     * @return string
     */
    public function getOriginalFileName(File $file): string
    {
        return pathinfo($file->getFilename(), PATHINFO_FILENAME);
    }

}
