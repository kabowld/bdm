<?php
declare(strict_types=1);

namespace App\Service;

use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Error\Error;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Handles Twig errors
 */
class HandleFrontError
{
    private const EXCEPTION_ERROR_TPL = "Exception/exception.html.twig";
    private const RUNTIME_ERROR_SERVICE = "Runtime Error: %s";
    private const LOAD_ERROR_MESSAGE = "Error load template at line: %s, %s, source name: %s and path: %s";
    const ERROR_DELETE_ITEM = 'Erreur lors de la suppression !';

    private Environment $twig;

    private LoggerInterface $logger;

    /**
     * HandleError constructor.
     *
     * @param Environment     $twig
     * @param LoggerInterface $logger
     */
    public function __construct(Environment $twig, LoggerInterface $logger)
    {
        $this->twig = $twig;
        $this->logger = $logger;
    }

    /**
     * Return the exception template
     *
     * @param string $message    Message when an exception occur
     * @param int    $statusCode StatusCode when an exception occur
     *
     * @return string|null
     */
    public function getErrorTpl(string $message, int $statusCode): ?string
    {
        try {
            return $this->twig->render(self::EXCEPTION_ERROR_TPL, compact('statusCode', 'message'));
        }
        catch (LoaderError|RuntimeError|SyntaxError $e) {
            $this->logTwigRender($e);
        }

        return null;
    }

    /**
     * Log twig errors
     *
     * Log twig errors
     *
     * @param Error $e
     *
     * @return void
     */
    private function logTwigRender(Error $e): void
    {
        $this->logger->error(
            sprintf(
                self::LOAD_ERROR_MESSAGE,
                $e->getTemplateLine(),
                $e->getRawMessage(),
                $e->getSourceContext()? $e->getSourceContext()->getName(): null,
                $e->getSourceContext()? $e->getSourceContext()->getPath(): null
            )
        );
        $this->logger->error(sprintf(self::RUNTIME_ERROR_SERVICE, $e->getMessage()), ['exception' => $e]);
        $this->logger->info($e->getTraceAsString(), ['exception' => $e]);
    }
}
