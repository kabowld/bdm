<?php
declare(strict_types=1);

namespace App\Manager;

use App\Entity\Groupe;
use App\Entity\User;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserManager extends Manager
{
    use LoggerAwareTrait;

    const MESS_ERROR_RANDOM_STR = 'Random string error: %s';
    const LENGTH_CONFIRMATION_TOKEN = 120;
    const ADMIN_DASHBOARD_PATH = 'admin_dashboard_bdmk';
    const ERROR_MESS_NOT_FOUND = 'La page que vous recherchez est introuvable !';
    const DURATION_TOKEN_VALIDATE = 3600;


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
            return bin2hex(random_bytes(self::LENGTH_CONFIRMATION_TOKEN));
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

    /**
     * @param User $user
     * @param string $role
     */
    public function registration(User $user, string $role)
    {
        $groupe = $this->em->getRepository(Groupe::class)->findOneBy(['role' => $role]);
        $user->setConfirmatoken($this->setConfirmationToken(self::LENGTH_CONFIRMATION_TOKEN));
        $user->setGroupe($groupe);
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     * @return string[]
     */
    public function validateAccount(User $user)
    {
        if (!$this->checkDateLinkExpiration($user)) {
            $user->setConfirmatoken(null);
            $this->em->flush();

            return ['status' => 'fail', 'message' => 'Désolé le lien d\'activation a expiré'];
        }

        $user
            ->setIsVerified(true)
            ->setConfirmatoken(null)
            ->setConfirmationAt(new \DateTimeImmutable())
            ->setEnabled(true)
        ;
        $this->em->flush();

        return ['status' => 'success', 'message' => 'Bravo, votre compte a été activé avec succès !'];
    }

    /**
     * Check expiration link
     *
     * @param User $user
     *
     * @return bool
     */
    private function checkDateLinkExpiration(User $user): bool
    {
        $result = (new \DateTimeImmutable())->getTimestamp() - $user->getCreatedAt()->getTimestamp();
        if ($result > self::DURATION_TOKEN_VALIDATE) {
            return false;
        }

        return true;
    }
}
