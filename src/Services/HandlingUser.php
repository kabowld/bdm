<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class HandlingUser
{
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
}
