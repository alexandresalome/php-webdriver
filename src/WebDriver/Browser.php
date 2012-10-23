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
 * WebDriver Browser. Represents a given browser launch, and methods to
 * manipulate this browser instance.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class Browser
{
    /**
     * @var string
     */
    protected $sessionId;

    /**
     * @var Client
     */
    protected $client;

    /**
     * Instanciates the object.
     *
     * @param Client $client    The client to use for exchanges with the
     *                          server
     * @param string $sessionId The session ID
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
        $this->request('POST', 'url', json_encode(array('url' => $url)));

        return $this;
    }

    /**
     * Returns the current session URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->getValue('url');
    }

    /**
     * Moves one toward in history.
     *
     * @return Session
     */
    public function forward()
    {
        $this->request('POST', 'forward');

        return $this;
    }

    /**
     * Moves one back in history.
     *
     * @return Session
     */
    public function back()
    {
        $this->request('POST', 'back');

        return $this;
    }

    /**
     * Closes the session and disable this session.
     */
    public function close()
    {
        $this->client->closeBrowser($this->getSessionId());
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
        return base64_decode($this->getValue('screenshot'));
    }

    /**
     * Requests the source code of the current page.
     *
     * @return string The source code of the current page
     */
    public function getSource()
    {
        return $this->getValue('source');
    }

    /**
     * Requests the title of the current page.
     *
     * @return string The page title
     */
    public function getTitle()
    {
        return $this->getValue('title');
    }

    /**
     * Method to select an element, using a selector (css, xpath, etc.).
     *
     * @param By $by Indicates how to search for the element
     *
     * @see By
     */
    public function element(By $by)
    {
        $response = $this->request('POST', 'element', json_encode($by->toArray()));
        $data = json_decode($response->getContent(), true);

        if (!isset($data['value']['ELEMENT'])) {
            throw new \RuntimeException('Missing key value.ELEMENT');
        }
        $id = $data['value']['ELEMENT'];

        return new Element($this, $id);
    }

    /**
     * Ease requesting in session directory.
     *
     * @return Response
     */
    public function request($verb, $path, $content = null, array $headers = array())
    {
        return $this->client->request($verb, sprintf('/session/%s/%s', $this->sessionId, $path), $content, $headers);
    }

    /**
     * Internal method to get a scalar value from server.
     *
     * @return mixed
     */
    protected function getValue($name)
    {
        $response = $this->client->request('GET', sprintf('/session/%s/%s', $this->sessionId, $name));
        $content = json_decode($response->getContent(), true);

        if (!isset($content['value'])) {
            throw new \RuntimeException('Malformed response: expected a key "value" in JSON response: '.$response->getContent());
        }

        return $content['value'];
    }
}
