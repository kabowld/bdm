<?php

namespace App\Tests\TwigExtension;

use App\TwigExtension\DurationPublishedAnnonceTwigExtension;
use PHPUnit\Framework\TestCase;

class DurationPublishedAnnonceTwigExtensionTest extends TestCase
{


    public function testDuration()
    {
        $date = new \DateTimeImmutable('2 days ago');
        $ext = new DurationPublishedAnnonceTwigExtension();

        $this->assertSame('Il y a 2 jours', $ext->getDuration($date));

        $date = new \DateTimeImmutable('yesterday');
        $ext = new DurationPublishedAnnonceTwigExtension();

        $this->assertSame('Hier', $ext->getDuration($date));

        $date = new \DateTimeImmutable('1990-03-23');
        $ext = new DurationPublishedAnnonceTwigExtension();

        $this->assertSame('Le 1990-03-23', $ext->getDuration($date));

    }
}
