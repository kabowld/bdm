<?php

namespace App\Tests\EventListener;

use App\EventListener\ExceptionListener;
use App\Service\HandleFrontError;
use PHPUnit\Framework\MockObject\Rule\InvocationOrder;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ExceptionListenerTest extends TestCase
{
    const EXCEPTION_MESSAGE_DISPATCH = 'Exception dispatch';
    const PAGE_NOT_FOUND = 'Page introuvable';

    private $handleError;

    private $logger;

    private $httpKernel;

    public function setUp(): void
    {
        $this->handleError = $this->createMock(HandleFrontError::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->httpKernel = $this->createMock(HttpKernelInterface::class);
    }

    /**
     * Test Exception Listener when exception throw
     */
    public function testExceptionListenerOnProdEnvironnement()
    {
        $message = sprintf('Internal server error, message: %s', self::EXCEPTION_MESSAGE_DISPATCH);
        $this->logger
            ->expects($this->once())
            ->method('critical')
            ->with($this->equalTo($message), $this->isType('array'));

        $this->renderTwigError(
            $this->once(),
            $this->equalTo(ExceptionListener::MESSAGE_INTERNAL_SERVER_ERROR),
            $this->isType('integer')
        );

        $this->dispatchEvent('prod');
    }

    /**
     * Test Exception Listener when exception throw
     */
    public function testExceptionListenerOnDevEnvironnement()
    {
        $message = sprintf('Internal server error, message: %s', self::EXCEPTION_MESSAGE_DISPATCH);
        $this->logger
            ->expects($this->once())
            ->method('critical')
            ->with($this->equalTo($message), $this->isType('array'));

        $this->renderTwigError(
            $this->never(),
            $this->equalTo(ExceptionListener::MESSAGE_INTERNAL_SERVER_ERROR),
            $this->isType('integer')
        );

        $this->dispatchEvent('dev');
    }

    /**
     * Test Exception Listener when HttpException throw
     */
    public function testHttpExceptionListenerOnProdEnvironnement()
    {
        $this->logger
            ->expects($this->once())
            ->method('error')
            ->with($this->equalTo('A HttpException was thrown !'), $this->isType('array'));

        $this->renderTwigError(
            $this->once(),
            $this->equalTo(self::PAGE_NOT_FOUND),
            $this->isType('integer')
        );

        $this->dispatchEvent('prod', true);
    }

    /**
     * Test Exception Listener when HttpException throw
     */
    public function testHttpExceptionListenerOnDevEnvironnement()
    {
        $this->logger
            ->expects($this->once())
            ->method('error')
            ->with($this->equalTo('A HttpException was thrown !'), $this->isType('array'));

        $this->renderTwigError(
            $this->never(),
            $this->equalTo(self::PAGE_NOT_FOUND),
            $this->isType('integer')
        );

        $this->dispatchEvent('dev', true);
    }

    /**
     * Returns the dispatcher event
     */
    private function dispatchEvent(string $env, $http = false)
    {
        $listener = new ExceptionListener($this->handleError, $this->logger, $this->getContainerBagInterface($env));
        $dispatcher = new EventDispatcher();
        $dispatcher->addListener('kernel.exception', [$listener, 'onKernelException']);
        $http ?
            $dispatcher->dispatch($this->getHttpExceptionEvent(), 'kernel.exception') :
            $dispatcher->dispatch($this->getExceptionEvent(), 'kernel.exception');
    }

    private function getContainerBagInterface(string $env)
    {
        $container = $this->getMockBuilder(ContainerBagInterface::class)->getMock();
        $container
            ->method('get')
            ->with('kernel.environment')
            ->willReturn($env);

        return $container;
    }

    /**
     * Return Exception Kernel Event
     *
     * @return ExceptionEvent
     */
    private function getExceptionEvent()
    {
        return new ExceptionEvent($this->httpKernel, new Request(['/hello']), 1, new \Exception(self::EXCEPTION_MESSAGE_DISPATCH));
    }

    /**
     * Return Exception Kernel Event for HttpException
     *
     * @return ExceptionEvent
     */
    private function getHttpExceptionEvent()
    {
        return new ExceptionEvent($this->httpKernel, new Request(['/not-found']), 1, new HttpException(404, self::PAGE_NOT_FOUND));
    }


    private function renderTwigError(InvocationOrder $invocationRule, $message, $status)
    {
        $this->handleError
            ->expects($invocationRule)
            ->method('getErrorTpl')
            ->with($message, $status)
            ->willReturn('error twig');
    }
}
