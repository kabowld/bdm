<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Service\HandleFrontError;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Event listener that implements error handling
 *
 * Class ExceptionListener
 * @package App\EventListener
 */
class ExceptionListener
{
    const MESSAGE_HTTP_EXCEPTION = 'A HttpException was thrown !';
    const MESSAGE_INTERNAL_SERVER_ERROR = 'Erreur interne du serveur !';
    const MESSAGE_CRITICAL_ERROR = 'Internal server error, message: %s';
    const NO_RESPONSE_CONTENT = '';
    const KERNEL_ENV = 'kernel.environment';
    const PROD_ENV = 'prod';


    private HandleFrontError $handleError;

    private LoggerInterface $logger;

    private ContainerBagInterface $params;

    /**
     * ExceptionListener constructor.
     *
     * @param HandleFrontError $handleError
     * @param LoggerInterface $logger
     * @param ContainerBagInterface $params
     */
    public function __construct(HandleFrontError $handleError, LoggerInterface $logger, ContainerBagInterface $params)
    {
        $this->handleError = $handleError;
        $this->logger = $logger;
        $this->params = $params;
    }

    /**
     * Fires on an exception
     *
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $message = $exception->getMessage();

        if ($this->params->get(self::KERNEL_ENV) !== self::PROD_ENV) {
            return;
        }

        if ($exception instanceof HttpExceptionInterface) {
            $this->logger->info(self::MESSAGE_HTTP_EXCEPTION, ['exception' => $exception]);
            $event->setResponse(
                $this->setResponseException($exception->getStatusCode(), $message, $exception->getHeaders())
            );

            return;
        }


        $this->logger->critical(sprintf(self::MESSAGE_CRITICAL_ERROR, $exception->getMessage()), ['exception' => $exception]);
        $event->setResponse(
            $this->setResponseException(Response::HTTP_INTERNAL_SERVER_ERROR, self::MESSAGE_INTERNAL_SERVER_ERROR)
        );
    }

    /**
     * Return response for exception
     *
     * @param int    $statusCode
     * @param string $message
     * @param array  $headers
     *
     * @return Response
     */
    private function setResponseException(int $statusCode, string $message, array $headers = null): Response
    {
        $response = new Response();
        if (!is_null($headers)) {
            $response->headers->replace($headers);
        }

        return
            $response
                ->setStatusCode($statusCode)
                ->setContent($this->handleError->getErrorTpl($message, $statusCode) ?: self::NO_RESPONSE_CONTENT)
            ;

    }

}
