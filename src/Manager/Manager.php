<?php
declare(strict_types=1);

namespace App\Manager;

use App\Service\SendMail;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

abstract class Manager
{

    protected EntityManagerInterface $em;
    protected Security $security;
    protected UrlGeneratorInterface $urlGenerator;
    protected SendMail $email;
    protected PaginatorInterface $paginator;
    protected LoggerInterface $logger;
    protected string $localUrl;

    /**
     * HandlingUser constructor.
     *
     * @param EntityManagerInterface $em
     * @param Security               $security
     * @param UrlGeneratorInterface  $urlGenerator
     * @param SendMail               $email
     * @param PaginatorInterface     $paginator
     * @param LoggerInterface        $logger
     * @param string                 $localUrl
     */
    public function __construct(
        EntityManagerInterface $em,
        Security $security,
        UrlGeneratorInterface $urlGenerator,
        SendMail $email,
        PaginatorInterface $paginator,
        LoggerInterface $logger,
        string $localUrl
    )
    {
        $this->em = $em;
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
        $this->email = $email;
        $this->paginator = $paginator;
        $this->logger = $logger;
        $this->localUrl = $localUrl;
    }

    /**
     * @param string $className
     *
     * @return array
     */
    public function all(string $className): array
    {
        return $this->getEntityRepository($className)->findAll();
    }

    /**
     * @param string $className
     * @param mixed  $id
     *
     * @return object|null
     */
    public function find(string $className, $id): ?object
    {
        return $this->getEntityRepository($className)->find($id);
    }



    /**
     * @param object $object
     * @param bool   $persist
     *
     * @return void
     */
    public function persist(object $object, bool $persist = true): void
    {
        if ($persist) {
            $this->em->persist($object);
        }
        $this->em->flush();
    }

    /**
     * @param string $className
     *
     * @return EntityRepository
     */
    protected function getEntityRepository(string $className): EntityRepository
    {
        return $this->em->getRepository($className);
    }
}
