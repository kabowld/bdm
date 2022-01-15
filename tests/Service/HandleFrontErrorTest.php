<?php

namespace App\Tests\Service;

use App\Service\HandleFrontError;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Error\RuntimeError;
use TypeError;
use Symfony\Component\HttpFoundation\Response;

class HandleFrontErrorTest extends TestCase
{
    const MESS_NOT_FOUND = 'Not found';

    const MESS_ERROR = 'Erreur trigger';

    private $twig;

    private $logger;

    public function setUp(): void
    {
        $this->twig = $this->createMock(Environment::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    public function testInstanceErrorServiceWithoutDepends()
    {
        $this->expectException(\ArgumentCountError::class);
        new HandleFrontError();
    }

    public function testInstanceErrorServiceWithDepends()
    {
        $this->assertInstanceOf(
            HandleFrontError::class,
            new HandleFrontError($this->twig, $this->logger)
        );
    }

    public function testGetErrorTplWillReturnString()
    {
        $this->twig
            ->expects($this->once())
            ->method('render')
            ->with($this->isType('string'), $this->isType('array'))
        ;


        $errorService = new HandleFrontError($this->twig, $this->logger);
        $this->assertIsString($errorService->getErrorTpl(self::MESS_NOT_FOUND, Response::HTTP_NOT_FOUND));
    }

    public function testGetErrorFailTplWillReturnNull()
    {
        $this->twig
            ->method('render')
            ->with($this->isType('string'), $this->isType('array'))
            ->willThrowException(new RuntimeError(self::MESS_ERROR))
        ;

        $errorService = new HandleFrontError($this->twig, $this->logger);
        $this->assertNull($errorService->getErrorTpl(self::MESS_NOT_FOUND, Response::HTTP_NOT_FOUND));
    }

    public function testGetErrorTplWithInvalidParams()
    {
        $errorService = new HandleFrontError($this->twig, $this->logger);

        $this->expectException(TypeError::class);
        $errorService->getErrorTpl(Response::HTTP_INTERNAL_SERVER_ERROR, self::MESS_ERROR);
    }


}
