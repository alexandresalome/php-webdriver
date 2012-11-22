<?php

/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver\Tests;

use Buzz\Message\Response;

use WebDriver\Exception\LibraryException;
use WebDriver\Browser;
use WebDriver\Client;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class BrowserTest extends \PHPUnit_Framework_TestCase
{
    protected $client;
    protected $browser;
    protected $buzzClient;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->buzzClient = new BuzzClientFIFO();
        $this->client     = new Client('http://localhost', $this->buzzClient);
        $this->browser    = new Browser($this->client, '12345');
    }

    public function testGetBrowserId()
    {

        $this->assertEquals('12345', $this->browser->getSessionId());
    }


    public function testOpen()
    {
        $response = new Response();
        $response->addHeader('1.0 200 OK');
        $this->buzzClient->sendToQueue($response);

        $this->browser->open('http://google.fr');

        $this->assertEquals(0, count($this->buzzClient->getQueue()));
    }

    public function testGetUrl()
    {
        $response = new Response();
        $response->addHeader('1.0 200 OK');
        $response->setContent(json_encode(array('value' => 'http://google.fr')));
        $this->buzzClient->sendToQueue($response);

        $url = $this->browser->getUrl();

        $this->assertEquals('http://google.fr', $url);
        $this->assertEquals(0, count($this->buzzClient->getQueue()));
    }

    public function testClose()
    {
        $response = new Response();
        $response->addHeader('1.0 200 OK');
        $this->buzzClient->sendToQueue($response);

        $this->browser->close();

        try {
            $this->browser->getSessionId();
            $this->fail();
        } catch (LibraryException $e) {
            $this->assertEquals('This session was closed', $e->getMessage());
        }
    }

    public function testScreenshot()
    {
        $response = new Response();
        $response->addHeader('1.0 200 OK');
        $response->setContent(json_encode(array('value' => base64_encode('!~éfoo'))));
        $this->buzzClient->sendToQueue($response);

        $data = $this->browser->screenshot();

        $this->assertEquals('!~éfoo', $data);
        $this->assertEquals(0, count($this->buzzClient->getQueue()));
    }

    public function testGetSource()
    {
        $response = new Response();
        $response->addHeader('1.0 200 OK');
        $response->setContent(json_encode(array('value' => 'foo')));
        $this->buzzClient->sendToQueue($response);

        $data = $this->browser->getSource();

        $this->assertEquals('foo', $data);
        $this->assertEquals(0, count($this->buzzClient->getQueue()));
    }

    public function testGetTitle()
    {
        $response = new Response();
        $response->addHeader('1.0 200 OK');
        $response->setContent(json_encode(array('value' => "foo")));
        $this->buzzClient->sendToQueue($response);

        $data = $this->browser->getTitle();

        $this->assertEquals('foo', $data);
        $this->assertEquals(0, count($this->buzzClient->getQueue()));
    }
}
