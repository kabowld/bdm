<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareTrait;

class HandlingUser
{
    use LoggerAwareTrait;

    const MESS_ERROR_RANDOM_STR = 'Random string error: %s';

    private $em;

    /**
     * HandlingUser constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
}
