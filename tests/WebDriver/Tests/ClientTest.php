<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver\Tests;

use Buzz\Message\Request;
use Buzz\Message\Response;

use WebDriver\Capabilities;
use WebDriver\Client;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideCreateBrowser
     */
    public function testCreateBrowser($withShortCapabilities)
    {
        $buzzClient = new BuzzClientFIFO();
        $client = new Client('http://localhost', $buzzClient);

        $response = new Response();
        $response->addHeader('1.0 302 Moved Temporarly');
        $response->addHeader('Location: http://localhost/session/12345');
        $buzzClient->sendToQueue($response);

        if ($withShortCapabilities) {
            $session = $client->createBrowser(new Capabilities('firefox'));
        } else {
            $session = $client->createBrowser('firefox');
        }

        $this->assertEquals(0, count($buzzClient->getQueue()), "Queue is empty");
        $this->assertEquals('12345', $session->getSessionId());
    }

    public function provideCreateBrowser()
    {
        return array(
            array(true),
            array(false)
        );
    }

    public function testGetBrowser()
    {
        $buzzClient = new BuzzClientFIFO();
        $client = new Client('http://localhost', $buzzClient);

        $response = new Response();
        $response->addHeader('1.0 302 Moved Temporarly');
        $response->addHeader('Location: http://localhost/session/12345');
        $buzzClient->sendToQueue($response);

        $session = $client->createBrowser(new Capabilities('firefox'));

        $this->assertEquals('12345', $session->getSessionId());
        $this->assertEquals($session, $client->getBrowser('12345'));

        try
        {
            $client->getBrowser('54321');
            $this->fail();
        } catch (\RuntimeException $e) {
            $this->assertEquals('The session "54321" was not found', $e->getMessage());
        }
    }

    public function testCloseBrowser()
    {
        $buzzClient = new BuzzClientFIFO();
        $client = new Client('http://localhost', $buzzClient);

        $response = new Response();
        $response->addHeader('1.0 200 OK');
        $buzzClient->sendToQueue($response);

        $session = $client->closeBrowser('12345');

        $this->assertEquals(0, count($buzzClient->getQueue()));
        try {
            $client->getBrowser('12345');
            $this->fail();
        } catch (\RuntimeException $e) {}
    }

    public function testDefaultClient()
    {
        $client = new Client('http://localhost/prefix');

        $object = new \ReflectionObject($client);
        $property = $object->getProperty('client');
        $property->setAccessible(true);
        $buzzClient = $property->getValue($client);

        $this->assertInstanceOf('Buzz\Client\Curl', $buzzClient);
        $this->assertEquals(Client::DEFAULT_TIMEOUT, $buzzClient->getTimeout());
        $this->assertEquals(0, $buzzClient->getMaxRedirects());
    }
}
