<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver;

use Buzz\Client\Curl;
use Buzz\Client\ClientInterface;
use Buzz\Message\Request;
use Buzz\Message\Response;

/**
 * Client for a WebDriver server.
 *
 * The client can mainly create new browser objects.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class Client
{
    const DEFAULT_TIMEOUT = 20000;

    /**
     * The base URL for WebDriver server.
     *
     * @var string
     */
    protected $url;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var array Browser objects, indexed by session ID
     */
    protected $browsers;

    /**
     * Constructs the client.
     *
     * @param string $url The base URL for WebDriver server
     *
     * @param Buzz\Client\ClientInterface $client The client to use for
     * requesting the WebDriver server
     */
    public function __construct($url, ClientInterface $client = null)
    {
        if (null === $client) {
            $client = new Curl();
            $client->setTimeout(self::DEFAULT_TIMEOUT);
            $client->setMaxRedirects(0);
        }

        $this->url      = $url;
        $this->client   = $client;
        $this->browsers = array();
    }

    /**
     * Creates a new browser with desired capabilities.
     *
     * @param mixed $capabilities Capabilities to request to the server. Value
     *                            can be a string (firefox) or a Capabilities
     *                            object.
     *
     * @return Browser
     */
    public function createBrowser($capabilities)
    {
        if (is_string($capabilities)) {
            $capabilities = new Capabilities($capabilities);
        } elseif (!$capabilities instanceof Capabilities) {
            throw new \InvalidArgumentException(sprintf('Expected a Capabilities or a string, given a "%s"', gettype($capabilities)));
        }

        $request  = new Message\Client\SessionCreateRequest($capabilities);
        $response = new Message\Client\SessionCreateResponse();

        $this->process($request, $response);

        $sessionId = $response->getSessionId();

        return $this->browsers[$sessionId] = new Browser($this, $sessionId);
    }

    /**
     * @return ClientStatus
     */
    public function getStatus()
    {
        $request  = new Message\Client\StatusRequest();
        $response = new Message\Client\StatusResponse();

        $this->process($request, $response);

        return $response->getStatus();
    }

    /**
     * Returns a browser associated to a session ID. To get it, it must have
     * been created.
     *
     * @param string $sessionId The session ID to fetch
     *
     * @return Browser
     *
     * @throws RuntimeException An exception is thrown if the session does not
     * exists.
     */
    public function getBrowser($sessionId)
    {
        if (!isset($this->browsers[$sessionId])) {
            throw new \RuntimeException(sprintf('The session "%s" was not found', $sessionId));
        }

        return $this->browsers[$sessionId];
    }

    /**
     * Closes a session according to his ID.
     *
     * @param string $sessionId A session ID
     */
    public function closeBrowser($sessionId)
    {
        $request = new Message\Client\SessionCloseRequest($sessionId);
        $response = new Response();

        $this->process($request, $response);

        unset($this->browsers[$sessionId]);
    }

    /**
     * Plumber method to request the server, using the base URL.
     *
     * @param Buzz\Message\Request $request The request to send
     *
     * @param Buzz\Message\Response $response The response to fill
     */
    public function process(Request $request, Response $response)
    {
        $url = $this->url.$request->getResource();

        $newRequest = clone $request;
        $newRequest->fromUrl($url);

        $newResponse = new Response();

        $this->client->send($newRequest, $newResponse);

        $this->verifyResponse($newResponse);

        $response->setHeaders($newResponse->getHeaders());
        $response->setContent($newResponse->getContent());
    }

    /**
     * Verifies every response received from the server to make sure no error
     * happened during processing.
     *
     * @param Buzz\Message\Response A response object to verify
     */
    protected function verifyResponse(Response $response)
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode === 200 || $statusCode === 204 || ($statusCode >= 300 && $statusCode <= 303)) {
            return;
        }

        $errorResponse = new Message\ErrorResponse();
        $errorResponse->setHeaders($response->getHeaders());
        $errorResponse->setContent($response->getContent());

        throw $errorResponse->getException();
    }
}
