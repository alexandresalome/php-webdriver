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

use WebDriver\Session;
use WebDriver\Client;

/**
 * Tests for the session object.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class SessionTest extends \PHPUnit_Framework_TestCase
{
    protected $client;
    protected $buzzClient;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->buzzClient = new BuzzClientFIFO();
        $this->client     = new Client('http://localhost', $this->buzzClient);
        $this->session    = new Session($this->client, '12345');
    }

    /**
     * Tests the getSessionId method.
     */
    public function testGetSessionId()
    {

        $this->assertEquals('12345', $this->session->getSessionId());
    }


    /**
     * Tests the open method
     */
    public function testOpen()
    {
        $response = new Response();
        $response->addHeader('1.0 200 OK');
        $this->buzzClient->sendToQueue($response);

        $this->session->open('http://google.fr');

        $this->assertEquals(0, count($this->buzzClient->getQueue()));
    }

    /**
     * Tests the getUrl method of the session
     */
    public function testGetUrl()
    {
        $response = new Response();
        $response->addHeader('1.0 200 OK');
        $response->setContent(json_encode(array('value' => 'http://google.fr')));
        $this->buzzClient->sendToQueue($response);

        $url = $this->session->getUrl();

        $this->assertEquals('http://google.fr', $url);
        $this->assertEquals(0, count($this->buzzClient->getQueue()));
    }

    /**
     * Tests the close method.
     */
    public function testClose()
    {
        $response = new Response();
        $response->addHeader('1.0 200 OK');
        $this->buzzClient->sendToQueue($response);

        $this->session->close();

        try {
            $this->session->getSessionId();
            $this->fail();
        } catch (\RuntimeException $e) {
            $this->assertEquals('This session was closed', $e->getMessage());
        }
    }

    public function testScreenshot()
    {
        $response = new Response();
        $response->addHeader('1.0 200 OK');
        $response->setContent(json_encode(array('value' => base64_encode('!~éfoo'))));
        $this->buzzClient->sendToQueue($response);

        $data = $this->session->screenshot();

        $this->assertEquals('!~éfoo', $data);
        $this->assertEquals(0, count($this->buzzClient->getQueue()));
    }

    public function testGetSource()
    {
        $response = new Response();
        $response->addHeader('1.0 200 OK');
        $response->setContent(json_encode(array('value' => 'foo')));
        $this->buzzClient->sendToQueue($response);

        $data = $this->session->getSource();

        $this->assertEquals('foo', $data);
        $this->assertEquals(0, count($this->buzzClient->getQueue()));
    }

    public function testGetTitle()
    {
        $response = new Response();
        $response->addHeader('1.0 200 OK');
        $response->setContent(json_encode(array('value' => "foo")));
        $this->buzzClient->sendToQueue($response);

        $data = $this->session->getTitle();

        $this->assertEquals('foo', $data);
        $this->assertEquals(0, count($this->buzzClient->getQueue()));
    }
}
