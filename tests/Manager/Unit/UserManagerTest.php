<?php

namespace App\Tests\Manager\Unit;

use App\Entity\Groupe;
use App\Entity\User;
use App\Exception\UserManagerException;
use App\Manager\UserManager;
use App\Service\SendMail;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class UserManagerTest extends TestCase
{
    private $em;
    private $email;

    public function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->email = $this->createMock(SendMail::class);
    }

    public function testRegistrationWithExpectedUserManagerException()
    {
        $repository = $this->createMock(EntityRepository::class);
        $repository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(false)
        ;

        $this->expectException(UserManagerException::class);
        $this->expectExceptionMessage('Rôle utilisateur inconnu !');
        $this->getUserManager($repository)->registration(new User(), 'ROLE_TEST');
    }

    public function testRegistrationWithNotCatchException()
    {
        $repository = $this->createMock(EntityRepository::class);
        $repository->expects($this->once())
            ->method('findOneBy')
            ->willReturn($this->getGroupe())
        ;

        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');

        $this->getUserManager($repository)->registration($this->getUser(), 'ROLE_TEST');
    }

    public function testCheckDateLinkExpirationWithOldDate()
    {
        $user = $this->getUser()->setCreatedAt(new \DateTimeImmutable('1988-10-10'));

        $this->assertFalse($this->getUserManager()->checkDateLinkExpiration($user));
    }

    public function testCheckDateLinkExpirationWithReturnTrue()
    {
        $user = $this->getUser()->setCreatedAt(new \DateTimeImmutable());

        $this->assertTrue($this->getUserManager()->checkDateLinkExpiration($user));
    }

    public function testFindUserByTokenWillExpectedUserManagerException()
    {
        $repository = $this->createMock(EntityRepository::class);
        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($this->isType('array'))
            ->willReturn(false)
        ;

        $token = bin2hex('abcsd');
        $this->expectException(UserManagerException::class);
        $this->expectExceptionMessage('La page que vous recherchez est introuvable !');
        $this->getUserManager($repository)->findUserByToken($token);
    }

    public function testFindUserWithReturnUserEntity()
    {
        $repository = $this->createMock(EntityRepository::class);
        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($this->isType('array'))
            ->willReturn($this->getUser())
        ;

        $token = bin2hex('abcsd');
        $this->assertInstanceOf(User::class, $this->getUserManager($repository)->findUserByToken($token));
    }

    public function testValidateAccountWithLinkExpired()
    {
        $user = $this->getUser()->setCreatedAt(new \DateTimeImmutable('1988-10-10'));
        $this->em->expects($this->once())->method('flush');

        $result = $this->getUserManager()->validateAccount($user);

        $this->assertSame('fail', $result['status']);
        $this->assertSame('Désolé le lien d\'activation a expiré', $result['message']);
    }

    public function testValidateAccount()
    {
        $user = $this->getUser()->setCreatedAt(new \DateTimeImmutable());
        $this->em->expects($this->once())->method('flush');

        $this->email
            ->expects($this->once())
            ->method('createEmail')
            ->with(
                $this->isType('string'),
                $this->equalTo('Bienvenue chez Bandama Market !'),
                $this->equalTo('Email/welcome.html.twig'),
                $this->isType('array')
            )
        ;

        $result = $this->getUserManager()->validateAccount($user);
        $this->assertSame('success', $result['status']);
        $this->assertSame('Bravo, votre compte a été activé avec succès !', $result['message']);
    }

    /**
     * @param $entityRepository
     *
     * @return UserManager
     */
    private function getUserManager($entityRepository = null): UserManager
    {
        $this->em->method('getRepository')->willReturn($entityRepository);
        $security = $this->createMock(Security::class);
        $generator = $this->createMock(UrlGeneratorInterface::class);
        $paginator = $this->createMock(PaginatorInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        return new UserManager($this->em, $security, $generator, $this->email, $paginator, $logger, '');
    }

    /**
     * @return Groupe
     */
    private function getGroupe(): Groupe
    {
        return (new Groupe())
            ->setTitle('admin')
            ->setRole('ROLE_ADMIN')
            ;
    }

    /**
     * @return User
     */
    private function getUser(): User
    {
        return (new User())
            ->setEmail('test@mail.dev')
            ->setPassword('abcde')
            ->setGroupe($this->getGroupe())
            ;
    }
}
