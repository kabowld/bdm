<?php


namespace App\Tests\Controller\Admin;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnnonceControllerTest extends WebTestCase
{
    public function testListeWithUserLoggedOut()
    {
        $client = static::createClient();
        $client->request('GET', '/admin/annonce/liste');

        $this->assertResponseRedirects('http://localhost/se-connecter', 302);
        $client->followRedirect();
        $this->assertSelectorTextContains('h3.txt-cg', 'Connectez-vous à votre compte');
    }


    public function testListeWithUserLoggedSuccessfully()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('dev@mail.test');

        $client->loginUser($testUser);
        $client->request('GET', '/admin/annonce/liste');
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1.title-hbx-admin', 'Mes annonces');
    }
}
