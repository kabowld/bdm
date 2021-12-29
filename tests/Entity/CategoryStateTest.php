<?php

namespace App\Tests\Entity;

use App\Entity\CategoryState;

class CategoryStateTest extends EntityTestCase
{

    public function addGoodProvider(): array
    {
        return [
            ['normal'],
            ['style'],
        ];
    }

    public function addBadProvider(): array
    {
        return [
            ['high'],
            ['medium'],
        ];
    }

    /**
     * @dataProvider addGoodProvider
     */
    public function testTypeWithGoodValue($value)
    {
        $category = new CategoryState();
        $category->setTitle($value);

        $this->assertHasErrors($category);
    }

    /**
     * @dataProvider addBadProvider
     */
    public function testTypeWithBadValue($value)
    {
        $category = new CategoryState();
        $category->setTitle($value);

        $this->assertHasErrors($category, 1);

        $category->setTitle('');

        $this->assertHasErrors($category, 2);
    }
}
