<?php

/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver\Tests;

use WebDriver\By;

/**
 * Windows-related features.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class WindowsTest extends AbstractTestCase
{
    public function testGetCurrent()
    {
        $this->markTestSkipped('Travis integration fails...');

        $browser = $this->getBrowser()->open($this->getUrl('index.php'));

        $actual = $browser->getWindows()->getCurrent();
        $this->assertTrue(is_string($actual));
    }

    public function testCollection()
    {
        $this->markTestSkipped('Travis integration fails...');

        $browser = $this->getBrowser()->open($this->getUrl('index.php'));

        $count = count($browser->getWindows()->getAll());

        $browser->element(By::linkText('Pop-up'))->click();
        $this->assertCount($count + 1, $all = $browser->getWindows()->getAll());

        $browser->element(By::linkText('Another page'))->click();
        $orig = $browser->getWindows()->getCurrent();
        $other = array_values(array_diff($all, array($orig)))[0];

        $browser->getWindows()->focus($other);
        sleep(1); // time for window manager
        $this->assertContains('Welcome to sample website', $browser->getText());

        $browser->getWindows()->closeCurrent();
        $browser->getWindows()->focus($orig);
        $this->assertContains('You know... some other page', $browser->getText());
    }

    public function testSize()
    {
        $this->markTestSkipped('Travis integration fails...');

        $browser = $this->getBrowser()->open($this->getUrl('index.php'));
        $win     = $browser->getWindows();

        // current window
        $size = $win->setSize(400, 300);
        sleep(1); // time for window manager
        $size = $win->getPosition();

        // Linux sticks to a grid, let's be... flexible!
        $this->assertGreaterThan(350, $size[0]);
        $this->assertLessThan(450, $size[0]);
        $this->assertGreaterThan(250, $size[1]);
        $this->assertLessThan(350, $size[1]);
    }

    public function testPosition()
    {
        $this->markTestSkipped('Travis integration fails...');

        $browser = $this->getBrowser()->open($this->getUrl('index.php'));
        $win     = $browser->getWindows();

        // current window, make it small to make it movable
        $win->setSize(100, 100)->setPosition(100, 200);
        sleep(1); // time for window manager
        $size = $win->getPosition();

        // Linux sticks to a grid, let's be... flexible!
        $this->assertGreaterThan(80, $size[0]);
        $this->assertLessThan(120, $size[0]);
        $this->assertGreaterThan(180, $size[1]);
        $this->assertLessThan(220, $size[1]);
    }

    public function testMaximize()
    {
        $this->markTestSkipped('Travis integration fails...');

        $browser = $this->getBrowser()->open($this->getUrl('index.php'));
        $win     = $browser->getWindows();

        $win->setSize(100, 100)->maximize();
        sleep(1); // time for window manager
        $size = $win->getSize();

        $this->assertGreaterThan(100, $size[0]);
        $this->assertGreaterThan(100, $size[1]);
    }
}
