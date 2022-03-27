<?php

namespace App\TwigExtension;

use App\Entity\Annonce;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FavorisTwigExtension extends AbstractExtension
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('favorisTag', [$this, 'createFavorisButton']),
        ];
    }

    /**
     * @param Annonce $annonce
     *
     * @return void
     */
    public function createFavorisButton(Annonce $annonce): void
    {
        $user = $this->security->getUser();
        /**
         * @var User $user
         */
        if (!$user->getFavoris()->contains($annonce)) {
            echo '<div class="float-left"><a href="#" class="fav-ann" data-id="'.$annonce->getId().'" data-action="enable"><i class="black-heart fa fa-heart-o"></i></a></div>';
        } else {
            echo '<div class="float-left"><a href="#" class="fav-ann" data-id="'.$annonce->getId().'" data-action="disable"><i class="black-heart fa fa-heart"></i></a></div>';
        }
    }
}
