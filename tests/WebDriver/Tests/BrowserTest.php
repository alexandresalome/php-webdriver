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
use WebDriver\Exception\ScriptTimeoutException;

/**
 * Browser-related features (no page crawling).
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class BrowserTest extends AbstractTestCase
{
    public function testScreenshot()
    {
        if (!class_exists('Imagick')) {
            $this->markTestSkipped('Imagick is not installed');
        }

        $browser = $this->getBrowser();
        $browser->open($this->getUrl('index.php'));

        $data = $browser->screenshot();

        $image = new \Imagick();
        $image->readimageblob($data);

        $this->assertGreaterThan(100, $image->getImageWidth());
        $this->assertGreaterThan(100, $image->getImageHeight());
    }

    public function testExecute()
    {
        $url     = $this->getUrl('index.php');
        $browser = $this->getBrowser();

        // Interaction with current page
        $browser->open($url);
        $title = $browser->execute('return document.title;');
        $this->assertEquals('Sample website', $title);

        // Arguments usage
        $expected = array(3, 4, true);
        $actual   = $browser->execute('return [arguments[0] + arguments[1], !arguments[2]];', $expected);
        $this->assertEquals(array(7, false), $actual);
    }

    public function testExecuteAsync()
    {
        $browser = $this->getBrowser()->open($this->getUrl('other.php'));

        // Normal
        $browser->setScriptTimeout(2000);
        $actual = $browser->executeAsync('var callback = arguments[arguments.length - 1]; window.setTimeout(function () { callback("foo"); }, 500);');
        $this->assertEquals("foo", $actual);

        // Timed-out
        $browser->setScriptTimeout(100);
        try {
            $browser->executeAsync('var callback = arguments[arguments.length - 1]; window.setTimeout(function () { callback("foo"); }, 300);');
            $this->fail();
        } catch (ScriptTimeoutException $e) {
            // OK
        }
    }

    public function testUrl()
    {
        $url = $this->getUrl('index.php');

        $browser = $this->getBrowser();
        $browser->open($url);

        $this->assertEquals($url, $browser->getUrl());
    }

    public function testBackAndForward()
    {
        $urlA = $this->getUrl('index.php');
        $urlB = $this->getUrl('page.php');

        $browser = $this->getBrowser();
        $browser->open($urlA);
        $browser->open($urlB);
        $browser->back();
        $this->assertRegExp('/index\.php$/', $browser->getUrl());
        $browser->forward();
        $this->assertRegExp('/page\.php$/', $browser->getUrl());
    }

    public function testRefresh()
    {
        $browser = $this->getBrowser();

        $browser->open($this->getUrl('rand.php'));
        $before = $browser->element(By::id('strike'))->getText();
        $browser->refresh();
        $after  = $browser->element(By::id('strike'))->getText();

        $this->assertTrue($before != $after, "The page was refreshed");
    }

    public function testTitle()
    {
        $browser = $this->getBrowser();
        $browser->open($this->getUrl('index.php'));

        $this->assertEquals('Sample website', $browser->getTitle());
    }

    public function testGetSource()
    {
        $browser = $this->getBrowser();
        $browser->open($this->getUrl('index.php'));

        $source = $browser->getSource();
        $this->assertContains('This comment is only viewable with source code', $source);
        $this->assertContains('<head>', $source);
        $this->assertContains('<body>', $source);
    }
}
