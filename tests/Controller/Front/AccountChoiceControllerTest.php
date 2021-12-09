<?php

namespace App\Tests\Controller\Front;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountChoiceControllerTest extends WebTestCase {

    public function testChoiceRegistrationWithUserLogged()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@mail.test');

        $client->loginUser($testUser);

        $client->request('GET', "/choix/compte");
        $this->assertResponseRedirects('/admin/dashboard', 302);
    }

    public function testChoiceRegistrationWithUserNotLogged()
    {
        $client = static::createClient();
        $client->request('GET', "/choix/compte");

        $this->assertResponseStatusCodeSame(200);
    }
}

