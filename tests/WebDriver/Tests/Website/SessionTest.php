<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver\Tests\Website;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class SessionTest extends AbstractTestCase
{
    public function testScreenshot()
    {
        if (!class_exists('Imagick')) {
            $this->markTestSkipped('Imagick is not installed');
        }

        $session = $this->getBrowser();
        $session->open($this->getUrl('index.php'));

        $data = $session->screenshot();

        $image = new \Imagick();
        $image->readimageblob($data);

        $this->assertGreaterThan(100, $image->getImageWidth());
        $this->assertGreaterThan(100, $image->getImageHeight());
    }

    public function testUrl()
    {
        $url = $this->getUrl('index.php');

        $session = $this->getBrowser();
        $session->open($url);

        $this->assertEquals($url, $session->getUrl());
    }

    public function testBackAndForward()
    {
        $urlA = $this->getUrl('index.php');
        $urlB = $this->getUrl('page.php');

        $session = $this->getBrowser();
        $session->open($urlA);
        $session->open($urlB);
        $session->back();
        $this->assertRegExp('/index\.php$/', $session->getUrl());
        $session->forward();
        $this->assertRegExp('/page\.php$/', $session->getUrl());
    }

    public function testTitle()
    {
        $session = $this->getBrowser();
        $session->open($this->getUrl('index.php'));

        $this->assertEquals('Sample website', $session->getTitle());
    }

    public function testGetSource()
    {
        $session = $this->getBrowser();
        $session->open($this->getUrl('index.php'));

        $source = $session->getSource();
        $this->assertContains('This comment is only viewable with source code', $source);
        $this->assertContains('<!DOCTYPE html>', $source);
        $this->assertContains('<head>', $source);
        $this->assertContains('<body>', $source);
    }
}
