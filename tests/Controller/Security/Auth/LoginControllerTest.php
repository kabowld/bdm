<?php

namespace App\Tests\Controller\Security\Auth;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase {

    public function testLogin()
    {
        $client = self::createClient();
        $client->request('GET', '/se-connecter');
        $this->assertResponseIsSuccessful();
    }

    public function testLoginIfUserLogged()
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@mail.test');

        $client->loginUser($testUser);

        $client->request('GET', '/se-connecter');

        $this->assertResponseRedirects('/admin/dashboard', 307);
    }

}
