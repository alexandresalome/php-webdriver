<?php

/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver\Tests\Website;

use WebDriver\By;

/**
 * Cookies-related features (no page crawling).
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class CookiesTest extends AbstractTestCase
{
    public function testDeleteAll()
    {
        $browser = $this->getBrowser();
        $browser->open($this->getUrl('cookies.php'));
        $browser->getCookies()->set('foo', 'bar');
        $browser->refresh();

        $this->assertNotContains('No cookie present', $browser->element(By::css('html'))->getText());

        $browser->getCookies()->deleteAll();
        $browser->refresh();

        $this->assertContains('No cookie present', $browser->element(By::css('html'))->getText());
    }

    public function testDelete()
    {
        $browser = $this->getBrowser();
        $browser->open($this->getUrl('cookies.php'));
        $browser->getCookies()->set('foo', 'bar');
        $browser->getCookies()->set('bar', 'baz');
        $browser->refresh();
        $browser->getCookies()->delete('bar');

        $this->assertEquals('bar', $browser->getCookies()->getValue('foo'));
        $this->assertNull($browser->getCookies()->get('bar'));
    }

    public function testSet()
    {
        $browser = $this->getBrowser();
        $browser->open($this->getUrl('cookies.php'));
        $browser->getCookies()->set('foo', 'bar');
        $browser->refresh();
        $this->assertEquals('bar', $browser->element(By::css('td[data-cookie="foo"]'))->getText());
    }

    public function testGetValue()
    {
        $browser = $this->getBrowser();
        $browser->open($this->getUrl('cookies.php'));
        $browser->getCookies()->set('foo', 'bar');
        $browser->refresh();
        $this->assertEquals('bar', $browser->getCookies()->getValue('foo'));
        $this->assertEquals(null, $browser->getCookies()->getValue('inexisting'));
    }

    public function testGet()
    {
        $url = $url = $this->getUrl('cookies.php');
        $dt = new \DateTime('+7 days');
        $browser = $this->getBrowser();
        $browser->open($url);

        $host = parse_url($url);
        $host = $host['host'];

        $browser->getCookies()->set('foo', 'bar');
        $browser->refresh();

        $cookie = $browser->getCookies()->get('foo');
        $this->assertInstanceOf('WebDriver\Cookie', $cookie);
        $this->assertEquals('foo', $cookie->getName());
        $this->assertEquals('bar', $cookie->getValue());

        $this->assertNull($browser->getCookies()->get('inexisting'));
    }
}
