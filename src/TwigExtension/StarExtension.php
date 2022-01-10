<?php

namespace App\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StarExtension extends AbstractExtension
{
    private const FA_STAR = '<i class="fa fa-star"></i>';

    public function getFunctions(): array
    {
        return [
            new TwigFunction('starTag', [$this, 'displayStars']),
        ];
    }

    public function displayStars(?int $stars)
    {
        if (is_null($stars)) {
            return null;
        }

        $html = '<span class="starTag">';
        while (true) {

            if (!($stars > 0)) {
                $html .= '</span>';
                break;
            }

            $html .= self::FA_STAR;
            $stars--;
        }

        echo $html;
    }

}
