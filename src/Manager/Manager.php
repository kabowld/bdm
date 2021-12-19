<?php


namespace App\Manager;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

abstract class Manager
{
    protected EntityManagerInterface $em;
    protected Security $security;
    protected UrlGeneratorInterface $urlGenerator;

    /**
     * HandlingUser constructor.
     *
     * @param Security $security
     * @param EntityManagerInterface $em
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(EntityManagerInterface $em, Security $security, UrlGeneratorInterface $urlGenerator)
    {
        $this->em = $em;
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
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
}
