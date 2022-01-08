<?php

namespace App\Tests\Entity;

use App\Entity\FilePicture;
use App\Entity\Rubrique;
use Symfony\Component\HttpFoundation\File\File;

class RubriqueTest extends EntityTestCase
{

    public function testWithBlankTitle()
    {
        $rubrique = $this->getRubrique();
        $rubrique->setTitle('');

        $this->assertHasErrors($rubrique, 1);
    }

    public function testBadLengthTitle()
    {
        $rubrique = $this->getRubrique();
        $rubrique->setTitle($this->str_random(51));

        $this->assertHasErrors($rubrique, 1);
    }

    public function testUniqueEntityTitle()
    {
        $rubrique = $this->getRubrique();
        $rubrique->setTitle('Divers');

        $this->assertHasErrors($rubrique, 1);
    }

    public function testWithBlankSlug()
    {
        $rubrique = $this->getRubrique();
        $rubrique->setSlug('');

        $this->assertHasErrors($rubrique, 1);
    }

    public function testBadLengthSlug()
    {
        $rubrique = $this->getRubrique();
        $rubrique->setSlug($this->str_random(51));

        $this->assertHasErrors($rubrique, 1);
    }

    /**
     * @return Rubrique
     */
    public static function getRubrique(): Rubrique
    {
        $image = new FilePicture();
        $image
            ->setFile(new File(__DIR__.DIRECTORY_SEPARATOR.'Files'.DIRECTORY_SEPARATOR.'logo.png'))
            ->setFileName('logo.png')
        ;

        return (new Rubrique())
            ->setTitle('Title rubrique')
            ->setSlug('title-rubrique')
            ->setDescription('rubrique description test')
            ->setImage($image)
       ;
    }


}
