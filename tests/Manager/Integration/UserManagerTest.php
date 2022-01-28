<?php

namespace App\Tests\Manager\Integration;

use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserManagerTest extends KernelTestCase
{
    public function fakeEmail(): array
    {
        return [
            [''],
            ['fakeEmail'],
            ['info.mail.test']
        ];
    }

    public function goodEmail(): array
    {
        return [
            ['bdm@mail.test'],
            ['arnaud@mail.test'],
            ['sf@gmail.com']
        ];
    }
    /**
     * @dataProvider fakeEmail
     *
     * @param string $expectedEmail
     *
     * @return void
     */
    public function testResetEmailPasswordWithInvalidEmail(string $expectedEmail)
    {
        self::bootKernel();
        $container = static::getContainer();

        $validator = $container->get(ValidatorInterface::class);

        $userManager = $container->get(UserManager::class);

        $response = $userManager->resetEmailPassword($validator, $expectedEmail);
        $content = json_decode($response->getContent(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertSame($content['status'], 'fail');
        $this->assertSame($content['message'], sprintf('L\'adresse mail "%s" n\'est pas valide !', $expectedEmail));
    }



    /**
     * @dataProvider goodEmail
     *
     * @param string $expectedEmail
     *
     * @return void
     */
    public function testResetEmailPasswordWithValidEmailWhichNotExist(string $expectedEmail)
    {
        self::bootKernel();
        $container = static::getContainer();

        $validator = $container->get(ValidatorInterface::class);

        $userManager = $container->get(UserManager::class);

        $response = $userManager->resetEmailPassword($validator, $expectedEmail);
        $content = json_decode($response->getContent(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertSame($content['status'], 'fail');
        $this->assertSame($content['message'], 'Aucun compte lié à cette adresse e-mail !');
    }

    /**
     * @return void
     */
    public function testResetEmailPasswordWithSuccess()
    {
        self::bootKernel();
        $container = static::getContainer();
        $expectedEmail = 'dev@mail.test';

        $validator = $container->get(ValidatorInterface::class);

        $userManager = $container->get(UserManager::class);

        $response = $userManager->resetEmailPassword($validator, $expectedEmail);
        $content = json_decode($response->getContent(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSame($content['status'], 'success');
        $this->assertSame($content['message'], 'Un e-mail vient de vous être envoyé !');
    }
}
