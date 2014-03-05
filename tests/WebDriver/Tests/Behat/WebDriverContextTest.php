<?php

namespace WebDriver\Tests\Behat;

use Behat\Gherkin\Node\TableNode;
use WebDriver\Behat\WebDriverContext;
use WebDriver\Browser;
use WebDriver\By;
use WebDriver\Tests  \AbstractTestCase;

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

    public function testIGoBackAndForward()
    {
        $ctx = $this->getContext($browser = $this->getBrowser());

        $browser->open($this->getUrl('/'));
        $browser->open($this->getUrl('/other.php'));

        $ctx->iGoBack();
        $this->assertNotContains('You know... some other page', $browser->getText());
        $this->assertContains('Welcome to sample website', $browser->getText());
        $ctx->iGoForward();
        $this->assertContains('You know... some other page', $browser->getText());
        $this->assertNotContains('Welcome to sample website', $browser->getText());
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
                $this->fail();
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
               $this->fail();
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
            $this->fail();
        } catch (\Exception $e) {
            // OK
        }

        // not existing xpath
        $browser->open($this->getUrl());
        try {
            $ctx->iClickOn('xpath=//abbr');
            $this->fail();
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

    public function testIShouldSee()
    {
        $ctx = $this->getContext($browser = $this->getBrowser());
        $browser->open($this->getUrl('/tree.php'));

        // correct
        $ctx->iShouldSee("2 ", "First floor");
        $ctx->iShouldSee("", "My home");
        $ctx->iShouldSee("", "Bedroom"); // 2 times on screen
        $ctx->iShouldSee("1 ", "My home");

        // incorrect
        try {
            $ctx->iShouldSee("2 ", "First floor");
            $this->fail();
        } catch (\Exception $e) {
            // OK
        }

        // correct
        $ctx->iShouldSee("2 ", "css=.home");
        $ctx->iShouldSee("", "css=#my-home");
        $ctx->iShouldSee("1 ", "css=#my-home");

        // incorrect
        try {
            $ctx->iShouldSee("", "css=.home");
            $this->fail();
        } catch (\Exception $e) {
            // OK
        }
    }

    public function testIShouldSeeIn()
    {
        $ctx = $this->getContext($browser = $this->getBrowser());
        $browser->open($this->getUrl('/tree.php'));

        try {
            $ctx->iShouldSeeIn('foo', 'bar');
        } catch (\Exception $e) {
            // OK
        }

        // select test
        $this->iShouldSeeIn('', 'id=select')
        $this->iFillWith('Select', 'bar label');
        $this->iShouldSeeIn('bar label', 'id=select')
    }

    public function testIShouldNotSee()
    {
        $ctx = $this->getContext($browser = $this->getBrowser());
        $browser->open($this->getUrl('/tree.php'));

        // correct
        $ctx->iShouldNotSee("Unknown floor");

        // incorrect
        try {
            $ctx->iShouldNotSee("My home");
            $this->fail();
        } catch (\Exception $e) {
            // OK
        }

        // correct
        $ctx->iShouldNotSee("css=.building");

        // incorrect
        try {
            $ctx->iShouldNotSee("css=.home");
            $this->fail();
        } catch (\Exception $e) {
            // OK
        }
    }

    public function testIFill()
    {
        $ctx = $this->getContext($browser = $this->getBrowser());
        $browser->open($this->getUrl('form.php'));

        // Fill EVERYTHING é/
        $table = new TableNode(<<<TABLE
| A text field | some value |
| A checkbox | 1 |
| Radio #1 | 1 |
| Select | foo label |
TABLE
        );

        $ctx->iFill($table);
        $browser->element(By::id('submit'))->click();
        $text = $browser->getText();

        $this->assertContains('Text field: some value', $text);
        $this->assertContains('Checkbox is checked', $text);
        $this->assertContains('Radio: 1', $text);
        $this->assertContains('Select: foo', $text);

        // check, verify, uncheck, verify
        $table = new TableNode('| A checkbox | 1 |');
        $ctx->iFill($table);
        $this->assertTrue($browser->element(By::id('checkbox'))->isSelected());
        $table = new TableNode('| A checkbox | 0 |');
        $ctx->iFill($table);
        $this->assertFalse($browser->element(By::id('checkbox'))->isSelected());

        // address a field by ID
        $browser->open($this->getUrl('form.php'));
        $ctx->iFill(new TableNode('| id=checkbox | 1 |'));
        $this->assertTrue($browser->element(By::id('checkbox'))->isSelected());
    }

    public function testIDeleteCookie()
    {
        $ctx = $this->getContext($browser = $this->getBrowser());
        $browser->open($this->getUrl('cookies.php')); // be on proper page to set/delete concerned cookies
        $browser->getCookies()->deleteAll();
        $browser->getCookies()->set('foo', 'foo value');
        $browser->getCookies()->set('bar', 'bar value');
        $browser->refresh();

        $text = $browser->getText();
        $this->assertContains('foo value', $text);
        $this->assertContains('bar value', $text);

        $ctx->iDeleteCookie('bar');
        $browser->refresh();

        $text = $browser->getText();
        $this->assertContains('foo value', $text);
        $this->assertNotContains('bar value', $text);

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
        $ctx->setBrowserInformations(function() use ($browser) {
            return $browser;
        }, $this->getUrl(), 0);

        return $ctx;
    }
}
