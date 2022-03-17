<?php

declare(strict_types=1);

namespace App\TwigExtension;

use DateTimeImmutable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DurationPublishedAnnonceTwigExtension extends AbstractExtension
{
    const TIME_DURATION = [
        'today' => [
            'timestamp' => 86400,
            'info' => 'Aujourd\' hui'
        ],
        'yesterday' => [
            'timestamp' => 172800,
            'info' => 'Hier'
        ],
        'twoDays' => [
            'timestamp' => 259200,
            'info' => 'Il y a 2 jours',
        ],
        'threeDays' => [
            'timestamp' => 345600,
            'info' => 'Il y a 3 jours'
        ]
    ];

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('duration', [$this, 'getDuration']),
        ];
    }

    /**
     * Get duration on published annonce
     *
     * @param DateTimeImmutable $dateTimeImmutable
     *
     * @return string
     */
    public function getDuration(DateTimeImmutable $dateTimeImmutable): string
    {
        $duration = (new DateTimeImmutable())->getTimestamp() - $dateTimeImmutable->getTimestamp();

        if ($duration <= self::TIME_DURATION['today']['timestamp']) {
            return self::TIME_DURATION['today']['info'];
        }

        if ($duration < self::TIME_DURATION['yesterday']['timestamp']) {
            return self::TIME_DURATION['yesterday']['info'];
        }

        if ($duration < self::TIME_DURATION['twoDays']['timestamp']) {
            return self::TIME_DURATION['twoDays']['info'];
        }

        if ($duration < self::TIME_DURATION['threeDays']['timestamp']) {
            return self::TIME_DURATION['threeDays']['info'];
        }

        return sprintf('Le %s', $dateTimeImmutable->format('Y-m-d'));
    }
}
