<?php


namespace App\Tests\Entity;


use App\Entity\User;

class UserTest extends EntityTestCase
{
    private function getUser(): User
    {
        return (new User())
            ->setEmail('test@mail.test')
            ->setPassword('P@ssw0rd')
            ->addRole('ROLE_TEST')
        ;
    }

    public function testWithBlankEmail()
    {
        $user = $this->getUser()->setEmail('');

        $this->assertHasErrors($user, 1);
    }

    public function testWithBadEmailLength()
    {
        $user = $this->getUser()->setEmail(sprintf('%s@mail.test', $this->str_random(180)));

        $this->assertHasErrors($user, 1);
    }

    /**
     * execute fixture before
     */
    public function testUniqueEmail()
    {
        $user = $this->getUser()->setEmail('dev@mail.test');

        $this->assertHasErrors($user, 1);
    }

    public function testBadPasswordAndInvalidSyntaxRole()
    {
        $user = $this->getUser()
            ->setEmail('test@test.mail')
            ->setPassword('RootRoot')
            ->setRoles(['ROLE_TEST_ONE', 'RO'])
        ;

        $this->assertHasErrors($user, 2);
    }

    public function testWithInvalidCivility()
    {
        $user = $this->getUser()
            ->setEmail('mad@test.mail')
            ->setCivility('miss')
        ;

        $this->assertHasErrors($user, 1);
    }

    public function testBadLengthForAnyEntities()
    {
        $user = $this->getUser()
            ->setEmail('miss@test.mail')
            ->setCivility($this->str_random(16))
            ->setTel($this->str_random(16))
            ->setSiret($this->str_random(60))
        ;

        $this->assertHasErrors($user, 4);
    }



}
