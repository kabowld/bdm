<?php
declare(strict_types=1);

namespace App\Manager;


use App\Entity\Annonce;
use Symfony\Component\Security\Core\User\UserInterface;

class AnnonceManager extends Manager
{
    /**
     * @param UserInterface $owner
     *
     * @return object[]
     */
    public function getMyAnnonces(UserInterface $owner)
    {
        return $this->em->getRepository(Annonce::class)->findBy(['owner' => $owner], ['createdAt' => 'DESC']);
    }


}
