<?php

namespace App\Tests\Entity;

use App\Entity\Groupe;

class GroupeTest extends EntityTestCase {

    /**
     * Test good hydration of the properties
     *
     * @return void
     */
    public function testGoodLengthAndSyntaxForTitleAndRole(): void
    {
        $groupe = $this->getInstance()
            ->setTitle('testTitleRole')
            ->setRole('ROLE_TEST_USER')
        ;
        $this->assertHasErrors($groupe);
    }

    /**
     * Tests with empty values
     *
     * @return void
     */
    public function testWithEmptyValueForTitleAndRole(): void
    {
        $groupe = $this->getInstance()
            ->setTitle('')
            ->setRole('')
        ;
        $this->assertHasErrors($groupe, 2);
    }

    /**
     * Test with error syntax on role property
     *
     * @return void
     */
    public function testWithBadSyntaxOfRole(): void
    {
        $groupe = $this->getInstance()
            ->setTitle('testTitleRole')
            ->setRole('role_test')
        ;
        $this->assertHasErrors($groupe, 1);
    }

    /**
     * Test with too many length on title and role
     *
     * @return void
     */
    public function testBadLengthTitleAndRole(): void
    {
        $groupe = $this->getInstance()
            ->setTitle($this->str_random(51))
            ->setRole('ROLE_'.strtoupper($this->str_random(51)))
        ;
        $this->assertHasErrors($groupe, 2);
    }

    /**
     * Test with all possibilities errors on properties
     * Bad length on title property
     * Bad length on role property
     * Bad syntax on role property
     *
     * @return void
     */
    public function testListErrorsTitleAndRole(): void
    {
        $groupe = $this->getInstance()
            ->setTitle($this->str_random(51))
            ->setRole('role_'.strtoupper($this->str_random(51)))
        ;
        $this->assertHasErrors($groupe, 3);
    }

    public function testUniqueRoleAndTitle(): void
    {
        $groupe = $this->getInstance()
            ->setTitle('Admin')
            ->setRole('ROLE_ADMIN')
        ;
        $this->assertHasErrors($groupe, 2);
    }

    private function getInstance(): Groupe
    {
        return new Groupe();
    }
}
