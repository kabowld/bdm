<?php
declare(strict_types=1);

namespace App\Core;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class Utils {

    const APP_ENV_DEV = 'dev';
    const APP_ENV_PROD = 'prod';
    const KERNEL_ENV = 'kernel.environment';

    private $params;
    private $logger;

    public function __construct(ContainerBagInterface $params, LoggerInterface $logger)
    {
        $this->params = $params;
        $this->logger = $logger;
    }

    /**
     * @return string|null
     */
    public function getUri(): ?string
    {
        try {
            switch ($this->params->get(self::KERNEL_ENV)) {
                case self::APP_ENV_DEV:
                    return $this->params->get(sprintf('app.url_local.%s',self::APP_ENV_DEV));
                    break;
                default:
                    return $this->params->get(sprintf('app.url_local.%s',self::APP_ENV_PROD));
            }
        } catch (NotFoundExceptionInterface $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            $this->logger->error(sprintf('Aucune entrée trouvé pour **%s**', self::KERNEL_ENV), ['exception' => $e]);
        } catch (ContainerExceptionInterface $e) {
            $this->logger->error(sprintf('Erreur déclenché lors de la réception de cette entrée **%s**', self::KERNEL_ENV), ['exception' => $e]);
            $this->logger->error($e->getMessage(), ['exception' => $e]);
        }

        return null;
    }
}
