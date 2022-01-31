<?php

namespace App\Tests\Controller\Security\Reset;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecoveryPasswordControllerTest extends WebTestCase
{
    public function testRecoveryPasswordIfUserLogged()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('dev@mail.test');
        $client->loginUser($testUser);

        $client->request('GET', '/rappel-mot-de-passe');

        $this->assertResponseRedirects('/admin/dashboard');
    }

    public function testRecoveryPasswordIfUserLoggedOut()
    {
        $client = static::createClient();
        $client->request('GET', '/rappel-mot-de-passe');

        $this->assertPageTitleSame('Mot de passe oublié | Bandama Market');
        $this->assertSelectorTextSame('.congrat_title', 'Mot de passe oublié');
        $this->assertResponseIsSuccessful();
    }

    public function testRecoveryPasswordInvalidEmail()
    {
        $client = static::createClient();
        $email = 'fakemail.test';
        $client->xmlHttpRequest('POST', '/rappel-mot-de-passe', ['email' => $email]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testRecoveryPasswordWithUnknownEmail()
    {
        $client = static::createClient();
        $email = 'fake@mail.test';
        $client->xmlHttpRequest('POST', '/rappel-mot-de-passe', ['email' => $email]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testRecoveryPasswordGoodEmail()
    {
        $client = static::createClient();
        $email = 'dev@mail.test';
        $client->xmlHttpRequest('POST', '/rappel-mot-de-passe', ['email' => $email]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }
}
