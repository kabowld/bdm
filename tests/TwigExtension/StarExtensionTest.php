<?php

namespace App\Tests\TwigExtension;

use App\TwigExtension\StarExtension;
use PHPUnit\Framework\TestCase;

class StarExtensionTest extends TestCase
{
    const STAR_TAG = '<i class="fa fa-star"></i>';

    public function testDisplayStarsReturnNull()
    {
        $starTwigExt = new StarExtension();

        $this->assertNull($starTwigExt->displayStars(null));
        $this->assertNull($starTwigExt->displayStars(0));
    }

    public function testDisplayStarsReturnOutputString()
    {
        $starTwigExt = new StarExtension();

        $this->expectOutputString(sprintf('<span class="starTag">%s</span>', self::STAR_TAG));
        $starTwigExt->displayStars(1);
    }

    public function testAgainDisplayStarsReturnOutputString()
    {
        $starTwigExt = new StarExtension();

        $this->expectOutputString(sprintf('<span class="starTag">%s%s</span>', self::STAR_TAG, self::STAR_TAG));
        $starTwigExt->displayStars(2);
    }
}
