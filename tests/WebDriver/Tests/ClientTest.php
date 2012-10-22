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
     * @dataProvider provideCreateSession
     */
    public function testCreateSession($withShortCapabilities)
    {
        $buzzClient = new BuzzClientFIFO();
        $client = new Client('http://localhost', $buzzClient);

        $response = new Response();
        $response->addHeader('1.0 302 Moved Temporarly');
        $response->addHeader('Location: http://localhost/session/12345');
        $buzzClient->sendToQueue($response);

        if ($withShortCapabilities) {
            $session = $client->createSession(new Capabilities('firefox'));
        } else {
            $session = $client->createSession('firefox');
        }

        $this->assertEquals(0, count($buzzClient->getQueue()), "Queue is empty");

        $this->assertInstanceOf('WebDriver\Message\Client\SessionCreateRequest', $buzzClient->getLastRequest());

        $this->assertEquals('12345', $session->getSessionId());
    }

    public function provideCreateSession()
    {
        return array(
            array(true),
            array(false)
        );
    }

    public function testGetSession()
    {
        $buzzClient = new BuzzClientFIFO();
        $client = new Client('http://localhost', $buzzClient);

        $response = new Response();
        $response->addHeader('1.0 302 Moved Temporarly');
        $response->addHeader('Location: http://localhost/session/12345');
        $buzzClient->sendToQueue($response);

        $session = $client->createSession(new Capabilities('firefox'));

        $this->assertEquals('12345', $session->getSessionId());
        $this->assertEquals($session, $client->getSession('12345'));

        try
        {
            $client->getSession('54321');
            $this->fail();
        } catch (\RuntimeException $e) {
            $this->assertEquals('The session "54321" was not found', $e->getMessage());
        }
    }

    public function testCloseSession()
    {
        $buzzClient = new BuzzClientFIFO();
        $client = new Client('http://localhost', $buzzClient);

        $response = new Response();
        $response->addHeader('1.0 200 OK');
        $buzzClient->sendToQueue($response);

        $session = $client->closeSession('12345');

        $this->assertInstanceOf('WebDriver\Message\Client\SessionCloseRequest', $buzzClient->getLastRequest());
        $this->assertEquals(0, count($buzzClient->getQueue()));
    }

    public function testPrefix()
    {
        $buzzClient = new BuzzClientFIFO();

        $response = new Response();
        $response->addHeader('1.0 200 OK');
        $buzzClient->sendToQueue($response);

        $client = new Client('http://localhost/prefix', $buzzClient);

        $request = new Request();
        $request->setResource('/session');
        $response = new Response();

        $client->process($request, $response);

        $this->assertEquals('/prefix/session', $buzzClient->getLastRequest()->getResource());
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

    public function testVerifyResponse()
    {
        $buzzClient = new BuzzClientFIFO();
        $client = new Client('http://localhost', $buzzClient);

        // Test the standard error
        $request  = new Request();
        $response = new Response();
        $response->addHeader('1.0 400 Bad Request');
        $response->setContent(json_encode(array('status' => 123, 'value' => array('message' => 'Message'))));
        $buzzClient->sendToQueue($response);

        try {
            $client->process($request, $response);
            $this->fail();
        } catch (\RuntimeException $e) {
            $this->assertEquals('Error 123: Message', $e->getMessage());
        }

        // Test the unparsable error
        $request  = new Request();
        $response = new Response();
        $response->addHeader('1.0 500 Internal Error');
        $response->setContent('Unparsable');
        $buzzClient->sendToQueue($response);

        try {
            $client->process($request, $response);
            $this->fail();
        } catch (\RuntimeException $e) {
            $this->assertEquals('Unparsable', $e->getMessage());
        }
    }
}
