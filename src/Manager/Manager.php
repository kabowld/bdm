<?php


namespace App\Manager;


use App\Service\SendMail;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

abstract class Manager
{

    protected EntityManagerInterface $em;
    protected Security $security;
    protected UrlGeneratorInterface $urlGenerator;
    protected SendMail $email;
    protected ContainerBagInterface $params;

    /**
     * HandlingUser constructor.
     *
     * @param EntityManagerInterface $em
     * @param Security $security
     * @param UrlGeneratorInterface $urlGenerator
     * @param SendMail $email
     */
    public function __construct(
        EntityManagerInterface $em,
        Security $security,
        UrlGeneratorInterface $urlGenerator,
        SendMail $email,
        ContainerBagInterface $params
    )
    {
        $this->em = $em;
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
        $this->email = $email;
        $this->params = $params;
    }

    public function all(string $className): array
    {
        return $this->em->getRepository($className)->findAll();
    }

    public function persist(object $object, bool $persist = true)
    {
        if ($persist) {
            $this->em->persist($object);
        }
        $this->em->flush();
    }

    /**
     * @return string|null
     */
    protected function getAppUrl(): ?string
    {
        try {
            return $this->params->get('app.url_local');
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            $this->logger->error($e, ['exception' => $e]);
        }

        return null;
    }
}
