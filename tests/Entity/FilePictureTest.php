<?php

namespace App\Tests\Entity;

use App\Entity\FilePicture;
use Symfony\Component\HttpFoundation\File\File;

class FilePictureTest extends EntityTestCase
{
    public function testWithBlankFileName()
    {
        $filePicture = $this->getFilePicture();
        $filePicture->setFileName('');

        $this->assertHasErrors($filePicture, 1);
    }

    private function getFilePicture(): FilePicture
    {
        $file = (new File(__DIR__.DIRECTORY_SEPARATOR.'Files'.DIRECTORY_SEPARATOR.'logo.png'));
        $filePicture = new FilePicture();
        $filePicture
            ->setFile($file)
            ->setFileName($file->getFilename())
        ;

        return $filePicture;
    }
}
