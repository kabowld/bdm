<?php

namespace App\Tests\Entity;

use App\Entity\Category;

class CategoryTest extends EntityTestCase
{

    public function testWithBlankTitle()
    {
        $category = $this->getCategory();
        $category->setTitle('');

        $this->assertHasErrors($category, 1);
    }

    public function testBadLengthTitle()
    {
        $category = $this->getCategory();
        $category->setTitle($this->str_random(51));

        $this->assertHasErrors($category, 1);
    }

    public function testUniqueEntityTitle()
    {
        $category = $this->getCategory();
        $category->setTitle('Ameublement');

        $this->assertHasErrors($category, 1);
    }

    public function testWithBlankSlug()
    {
        $category = $this->getCategory();
        $category->setSlug('');

        $this->assertHasErrors($category, 1);
    }

    public function testBadLengthSlug()
    {
        $category = $this->getCategory();
        $category->setSlug($this->str_random(51));

        $this->assertHasErrors($category, 1);
    }

    private function getCategory(): Category
    {
        return (new Category())
            ->setTitle('Title test')
            ->setSlug('Title-test')
            ->setDescription('description')
            ->setRubrique(RubriqueTest::getRubrique())
        ;
    }
}
