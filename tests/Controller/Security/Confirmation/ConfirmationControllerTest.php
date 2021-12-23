<?php


namespace App\Tests\Controller\Security\Confirmation;


use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ConfirmationControllerTest extends WebTestCase
{
    public function testValidationUserAccountThrowException()
    {
        $client = self::createClient();
        $client->request('GET', '/validation/compte/furhufuhuzdzh124254');
        $this->assertResponseStatusCodeSame(404);
    }

    public function testValidationUserAccountSuccess()
    {
        $client = self::createClient();
        $user = self::getContainer()->get(UserRepository::class)->findOneByEmail('dev@mail.test');

        /** @var User $user */
        $client->request('GET', sprintf('/validation/compte/%s', $user->getConfirmatoken()));
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorExists('h1.congrat_title', 'Bravo, votre compte a été activé avec succès !');
    }


    public function testValidationUserAccountFail()
    {
        $client = self::createClient();
        $user = self::getContainer()->get(UserRepository::class)->findOneByEmail('dev@mail.test');

        /** @var User $user */
        $user->getCreatedAt()->setTimestamp(84400);
        $client->request('GET', sprintf('/validation/compte/%s', $user->getConfirmatoken()));
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorExists('h1.congrat_title', 'Bravo, votre compte a été activé avec succès !');
    }

}
