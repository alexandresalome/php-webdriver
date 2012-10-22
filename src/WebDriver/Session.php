<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver;

use Buzz\Message\Response;

/**
 * WebDriver Session
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class Session
{
    /**
     * The session ID on the current server
     *
     * @var string
     */
    protected $sessionId;

    /**
     * The WebDriver server
     *
     * @var WebDriver\Client
     */
    protected $client;

    /**
     * Instanciates the object.
     *
     * @param string $sessionId The session ID
     *
     * @param WebDriver\Client $client The client to use for exchanges with the
     * server
     */
    public function __construct(Client $client, $sessionId)
    {
        $this->client     = $client;
        $this->sessionId  = $sessionId;
    }

    /**
     * Open a URL. The function will wait for page to load before returning the
     * value. If any redirection occurs, it will follow them before returning
     * a value.
     *
     * @param string $url A URL to access
     *
     * @return Session
     */
    public function open($url)
    {
        $request   = new Message\Navigation\UrlSetRequest($this->sessionId, $url);
        $response  = new Response();

        $this->client->process($request, $response);

        return $this;
    }

    /**
     * Returns the current session URL.
     *
     * @return string
     */
    public function getUrl()
    {
        $request   = new Message\Navigation\UrlGetRequest($this->sessionId);
        $response  = new Message\Navigation\UrlGetResponse();

        $this->client->process($request, $response);

        return $response->getUrl();
    }

    /**
     * Closes the session and disable this session.
     */
    public function close()
    {
        $this->client->closeSession($this->getSessionId());
        $this->sessionId = null;
    }

    /**
     * Returns the current session ID.
     *
     * @return string
     */
    public function getSessionId()
    {
        if (null === $this->sessionId) {
            throw new \RuntimeException('This session was closed');
        }

        return $this->sessionId;
    }

    /**
     * Captures a screenshot of the page, PNG format.
     *
     * @return string The PNG file content
     */
    public function screenshot()
    {
        $request  = new Message\Session\ScreenshotRequest($this->getSessionId());
        $response = new Message\Session\ScreenshotResponse();

        $this->client->process($request, $response);

        return $response->getScreenshotData();
    }

    /**
     * Requests the source code of the current page.
     *
     * @return string The source code of the current page
     */
    public function getSource()
    {
        $request  = new Message\Session\SourceRequest($this->getSessionId());
        $response = new Message\Session\SourceResponse();

        $this->client->process($request, $response);

        return $response->getSource();
    }

    /**
     * Requests the title of the current page.
     *
     * @return string The page title
     */
    public function getTitle()
    {
        $request  = new Message\Session\TitleRequest($this->getSessionId());
        $response = new Message\Session\TitleResponse();

        $this->client->process($request, $response);

        return $response->getTitle();
    }
}
