<?php

/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver\Tests  ;

use WebDriver\By;

class MouseTest extends AbstractTestCase
{
    public function testElementMoveTo()
    {
        $browser = $this->getBrowser();
        $browser->open($this->getUrl('mouse.php'));

        $this->assertNotContains('You see this text because of hover', $browser->getText());

        $browser->element(By::css('h1'))->moveTo();
        $browser->element(By::css('.hover-wrapper .title'))->moveTo();

        $this->assertContains('You see this text because of hover', $browser->getText());
    }

    public function testBrowserMoveTo()
    {
        $this->markTestSkipped('Fails...');
        $browser = $this->getBrowser();
        $browser->open($this->getUrl('mouse.php'));

        $browser->moveTo(20, 200);
        $this->assertContains('Position: 10-15', $browser->getText());
    }
}
