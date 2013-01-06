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

use WebDriver\Exception\ExceptionFactory;
use WebDriver\Exception\LibraryException;

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

    public function request($verb, $path, $content = null, array $headers = array())
    {
        $url = $this->url.$path;
        $request = new Request($verb, $url);
        $request->setContent($content);
        $response = new Response();

        $this->client->send($request, $response);
        $response->setContent(str_replace("\0", "", $response->getContent()));
        $this->verifyResponse($response);

        return $response;
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
            throw new LibraryException(sprintf('Expected a Capabilities or a string, given a "%s"', gettype($capabilities)));
        }

        $response = $this->request('POST', '/session', json_encode(array('desiredCapabilities' => $capabilities->toArray())));
        $sessionId = $this->getSessionIdFromRedirect($response);

        return $this->browsers[$sessionId] = new Browser($this, $sessionId);
    }

    /**
     * @return ClientStatus
     */
    public function getStatus()
    {
        $response = $this->request('GET', '/status');

        return ClientStatus::fromArray(json_decode($content, true));
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
            throw new LibraryException(sprintf('The session "%s" was not found', $sessionId));
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
        $this->request('DELETE', '/session/'.$sessionId);

        unset($this->browsers[$sessionId]);
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

        $content = json_decode($response->getContent(), true);
        if (null !== $content) {
            throw ExceptionFactory::createExceptionFromArray($content);
        } else {
            throw new LibraryException('Unparsable error: '.$response->getContent());
        }
    }

    protected function getSessionIdFromRedirect(Response $response)
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode !== 302) {
            throw new LibraryException(sprintf('The response should be a redirection, response code from server was "%s"', $statusCode));
        }

        $location = $response->getHeader('Location');
        if (!preg_match('#/session/([0-9a-f\-]+)?#', $location, $vars)) {
            throw new LibraryException(sprintf('The Location should end with /session/<session-id> (location returned: %s)', $location));
        }

        return $vars[1];

    }
}
