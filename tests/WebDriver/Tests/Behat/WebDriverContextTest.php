<?php

namespace WebDriver\Tests\Behat;

use WebDriver\Behat\WebDriverContext;
use WebDriver\Browser;
use WebDriver\Tests\Website\AbstractTestCase;

class WebDriverContextTest extends AbstractTestCase
{
    public function testIDeleteCookies()
    {
        $ctx = $this->getContext($browser = $this->getBrowser());

        $browser->open($this->getUrl('/'));
        $browser->getCookies()->set('foo', 'test cookie content');
        $browser->open($this->getUrl('/cookies.php'));
        $this->assertContains('test cookie content', $browser->getText());

        $ctx->iDeleteCookies();

        $browser->refresh();
        $this->assertNotContains('test cookie content', $browser->getText());
    }

    public function testIRefresh()
    {
        $ctx = $this->getContext($browser = $this->getBrowser());

        $browser->open($this->getUrl('/rand.php'));
        $expected = $browser->getText();

        $ctx->iRefresh();

        $this->assertNotEquals($expected, $browser->getText());

    }

    public function testIAmOn()
    {
        $ctx = $this->getContext($browser = $this->getBrowser());

        $ctx->iAmOn('/request.php');
        $this->assertContains('$_GET', $browser->getText());

        // escaping
        $ctx->iAmOn('/request.php?foo=bar""baz');
        $this->assertContains('bar"baz', $browser->getText());
    }

    public function testIShouldBeOn()
    {
        $ctx = $this->getContext($browser = $this->getBrowser());

        $browser->open($this->getUrl());
        $ctx->iShouldBeOn('/');
        $ctx->iShouldBeOn($this->getUrl());

        $browser->open($this->getUrl('/rand.php'));

        // correct values
        $ctx->iShouldBeOn('/rand.php');
        $ctx->iShouldBeOn('rand.php');
        $ctx->iShouldBeOn($this->getUrl('rand.php'));

        foreach (array('not-rand.php', '/foo/rand.php') as $incorrect) {
            try {
                $ctx->iShouldBeOn($incorrect);
            } catch (\Exception $e) {
                // OK
            }
        }
    }

    public function testTitleShouldBe()
    {
        $ctx = $this->getContext($browser = $this->getBrowser());

        $browser->open($this->getUrl());

        $ctx->titleShouldBe('Sample website');

        foreach (array('foo', 'Sample', 'website', 'Sample website  ') as $incorrect) {
            try {
                $ctx->titleShouldBe($incorrect);
            } catch (\Exception $e) {
                // OK
            }
        }

        // escaping
        $browser->execute('document.title = \'foo"bar"\';');
        $ctx->titleShouldBe('foo""bar""');
    }

    public function testIMoveMouseTo()
    {
        $ctx = $this->getContext($browser = $this->getBrowser());
        $browser->open($this->getUrl('mouse.php'));
        $this->assertNotContains('You see this text because of hover', $browser->getText());
        $ctx->iMoveMouseTo('xpath=//h2[contains(., "Hover me")]');
        $this->assertContains('You see this text because of hover', $browser->getText());
    }

    public function testIClickOn()
    {
        $ctx = $this->getContext($browser = $this->getBrowser());

        // not existing text
        $browser->open($this->getUrl());
        try {
            $ctx->iClickOn('Not existing text');
        } catch (\Exception $e) {
            // OK
        }

        // not existing xpath
        $browser->open($this->getUrl());
        try {
            $ctx->iClickOn('xpath=//abbr');
        } catch (\Exception $e) {
            // OK
        }

        // partial text
        $ctx->iClickOn('random hash');
        $this->assertContains('Random strike', $browser->getText());

        // full text
        $browser->open($this->getUrl());
        $ctx->iClickOn('random hash');
        $this->assertContains('Random strike', $browser->getText());

        // by
        $browser->open($this->getUrl());
        $ctx->iClickOn('xpath=//a[contains(., "A link to a random hash")]');
        $this->assertContains('Random strike', $browser->getText());
    }

    // Abstract method tests

    public function testGetUrl()
    {
        $ctx = $this->getContext($this->getBrowser());

        $this->assertEquals($this->getUrl(), $ctx->getUrl());
        $this->assertEquals($this->getUrl(), $ctx->getUrl('/'));
        $this->assertEquals($this->getUrl('foo'), $ctx->getUrl('foo'));
        $this->assertEquals('http://google.fr', $ctx->getUrl('http://google.fr'));
    }

    private function getContext(Browser $browser)
    {

        $ctx = new WebDriverContext();
        $ctx->setShouldSeeTimeout(0);
        $ctx->setBrowserInformations(function() use ($browser) {
            return $browser;
        }, $this->getUrl());


        return $ctx;
    }
}
