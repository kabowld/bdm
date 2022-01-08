<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * DeleteRubriqueFiles
 *
 * class to do processing on files
 */
class DeleteRubriqueFiles
{
    private string $rubriqueDirectory;

    public function __construct(string $rubriqueDirectory)
    {
        $this->rubriqueDirectory = $rubriqueDirectory;
    }

    /**
     * Remove files on directory $rubriqueDirectory
     *
     * @return bool
     */
    public function remove(): bool
    {
        $finder = new Finder();
        $finder->files()->in($this->rubriqueDirectory);

        if (!$finder->hasResults()) {
            return false;
        }

        foreach ($finder as $file) {
            (new Filesystem())->remove($file);
        }

        return true;
    }
}
