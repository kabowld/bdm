<?php

namespace App\Tests\Controller\Admin;


use App\Repository\PackRepository;
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

    public function testPackInfosWithMethodNotAllowed()
    {
        $client = static::createClient();

        $client->request('GET', '/admin/annonce/pack/infos');

        $this->assertResponseStatusCodeSame('405');
    }

    public function testPackInfosWithOutXmlHttpRequest()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('dev@mail.test');

        $client->loginUser($testUser);


        $client->request('POST', '/admin/annonce/pack/infos', ['id' => 1]);
        $this->assertResponseStatusCodeSame(404);
        $this->assertSelectorTextContains('title', 'La page est introuvable !');
    }

    public function testPackInfosWithSuccessFull()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $pack = static::getContainer()->get(PackRepository::class)->findOneBy(['days' => 7]);
        $testUser = $userRepository->findOneByEmail('dev@mail.test');

        $client->loginUser($testUser);
        $client->xmlHttpRequest('POST', '/admin/annonce/pack/infos', ['id' => $pack->getId()]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testPackInfosWithUnSuccessFull()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('dev@mail.test');

        $client->loginUser($testUser);
        $client->xmlHttpRequest('POST', '/admin/annonce/pack/infos', ['id' => 999]);

        $this->assertResponseStatusCodeSame(404);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }
}
