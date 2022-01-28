<?php

namespace App\Tests\Manager\Integration;

use App\Entity\Pack;
use App\Entity\Rubrique;
use App\Manager\AnnonceManager;
use App\Repository\PackRepository;
use App\Repository\RubriqueRepository;
use App\Service\SendMail;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class AnnonceManagerTest extends KernelTestCase
{

    const FAKE_ID = 999;

    public function testGetResponsePackInfosWithErrorMessage()
    {
        self::bootKernel();

        $pack = static::getContainer()->get(PackRepository::class)->find(self::FAKE_ID);
        $response = $this->gatAnnonceManager(Pack::class, $pack)->getResponsePackInfos(self::FAKE_ID);
        $data = json_decode($response->getContent(), true);

        $this->assertResponseHasFailed($response, $data);
    }

    public function testGetResponsePackInfosWithSuccessMessage()
    {
        self::bootKernel();

        $pack = static::getContainer()->get(PackRepository::class)->findOneBy(['days' => 7]);
        $response = $this->gatAnnonceManager(Pack::class, $pack)->getResponsePackInfos($pack->getId());

        $data = json_decode($response->getContent(), true);

        $this->assertResponseHasSuccess($response, $data);
    }

    public function testGetResponseRubriqueInfosWithErrorMessage()
    {
        self::bootKernel();

        $rubrique = static::getContainer()->get(RubriqueRepository::class)->find(self::FAKE_ID);
        $response = $this->gatAnnonceManager(Rubrique::class, $rubrique)->getResponseRubriqueInfos(self::FAKE_ID);
        $data = json_decode($response->getContent(), true);

        $this->assertResponseHasFailed($response, $data);
    }

    public function testGetResponseRubriqueInfosWithSuccessMessage()
    {
        self::bootKernel();

        $rubrique = static::getContainer()->get(RubriqueRepository::class)->findOneBy(['title' => 'Divers']);
        $response = $this->gatAnnonceManager(Rubrique::class, $rubrique)->getResponseRubriqueInfos($rubrique->getId());

        $data = json_decode($response->getContent(), true);

        $this->assertResponseHasSuccess($response, $data);
    }

    /**
     * @param Response $response
     * @param array    $data
     *
     * @return void
     */
    private function assertResponseHasSuccess(Response $response, array $data): void
    {
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('content-type'));
        $this->assertSame('success', $data['message']);
    }

    /**
     * @param Response $response
     * @param array    $data
     *
     * @return void
     */
    private function assertResponseHasFailed(Response $response, array $data): void
    {
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('content-type'));
        $this->assertSame('error', $data['message']);;
    }

    /**
     * @param string $classname
     * @param $object
     *
     * @return AnnonceManager
     */
    private function gatAnnonceManager(string $classname, $object): AnnonceManager
    {
        $entityRepository = $this->createMock(EntityRepository::class);
        $entityRepository->method('find')->willReturn($object);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->method('getRepository')->with($classname)->willReturn($entityRepository);

        $security = $this->createMock(Security::class);
        $generator = $this->createMock(UrlGeneratorInterface::class);
        $email = $this->createMock(SendMail::class);
        $paginator = $this->createMock(PaginatorInterface::class);
        $logger = $this->createMock(LoggerInterface::class);

        return new AnnonceManager($em, $security, $generator, $email, $paginator, $logger, '');
    }
}
