<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class HandlingUser
{
    use LoggerAwareTrait;

    const MESS_ERROR_RANDOM_STR = 'Random string error: %s';

    private $em;
    private $security;
    private $urlGenerator;

    /**
     * HandlingUser constructor.
     *
     * @param EntityManagerInterface $em
     * @param Security               $security
     */
    public function __construct(EntityManagerInterface $em, Security $security, UrlGeneratorInterface $urlGenerator)
    {
        $this->em = $em;
        $this->security = $security;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Update lastLogin
     *
     * @param User $user
     *
     * @return void
     */
    public function updateLastLogin(User $user): void
    {
        $user->setLastLogin(new \DateTimeImmutable());

        $this->em->flush();
    }

    /**
     * @param int $length
     *
     * @return false|string
     */
    public function setConfirmationToken(int $length)
    {
        try {
            return bin2hex(random_bytes(60));
        } catch (\Exception $e) {
            $this->logger->error(sprintf(self::MESS_ERROR_RANDOM_STR, $e->getMessage()), ['exception' => $e]);
        }

        $alphabet = '0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN';

        return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
    }

    /**
     * @return RedirectResponse|null
     */
    public function redirectUserIfLogged(): ?RedirectResponse
    {
        if ($this->security->getUser()) {
            return new RedirectResponse($this->urlGenerator->generate('admin_dashboard_bdmk'));
        }

        return null;
    }
}
