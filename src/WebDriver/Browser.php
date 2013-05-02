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

use WebDriver\Exception\LibraryException;

/**
 * Root entry to manipulate browser.
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
     * NOTE: This attribute is private because lately, it might be necessary
     * to refactor this part.
     *
     * @var Client
     */
    private $client;

    /**
     * @var CookieBag
     */
    private $cookieBag;

    /**
     * @var boolean
     */
    protected $closeOnDestruct = true;

    /**
     * A shortcut method to quickly get a browser up and running.
     *
     * @return Browser fluid interface
     */
    static public function create($capabilities, $url)
    {
        $client = new Client($url);

        return $client->createBrowser($capabilities);
    }

    /**
     * Instanciates the object.
     *
     * @param Client $client    The client to use for exchanges with the server
     * @param string $sessionId The session ID
     */
    public function __construct(Client $client, $sessionId)
    {
        $this->client     = $client;
        $this->sessionId  = $sessionId;
    }

    public function getCookies()
    {
        if (null === $this->cookieBag) {
            $this->cookieBag = new CookieBag($this);
        }

        return $this->cookieBag;
    }

    /**
     * Open a URL. The function will wait for page to load before returning the
     * value. If any redirection occurs, it will follow them before returning
     * a value.
     *
     * @param string $url A URL to access
     *
     * @return Browser fluid interface
     */
    public function open($url)
    {
        $this->request('POST', 'url', json_encode(array('url' => $url)));

        return $this;
    }

    /**
     * Run a Javascript snippet on browser.
     *
     * @param string $javascript The javascript snippet to execute
     * @param array  $args       Arguments to pass to snippet (accessible by arguments[0], ...)
     *
     * @return mixed Result of javascript execution
     */
    public function execute($javascript, array $args = array())
    {
        $response = $this->request('POST', 'execute', json_encode(array(
            'script' => $javascript,
            'args'   => $args
        )));

        $data = json_decode($response->getContent(), true);

        return $data['value'];
    }

    /**
     * Returns current URL.
     *
     * @return string a URL
     */
    public function getUrl()
    {
        return $this->requestValue('url');
    }

    /**
     * Moves one toward in history.
     *
     * @return Browser fluid interface
     */
    public function forward()
    {
        $this->request('POST', 'forward');

        return $this;
    }

    /**
     * Moves one back in history.
     *
     * @return Browser fluid interface
     */
    public function back()
    {
        $this->request('POST', 'back');

        return $this;
    }

    /**
     * Request browser to refresh current page.
     *
     * @return Browser fluid interface
     */
    public function refresh()
    {
        $this->request('POST', 'refresh');

        return $this;
    }

    /**
     * Closes the session and disable this session.
     *
     * @return Browser fluid interface
     */
    public function close()
    {
        $this->client->closeBrowser($this->getSessionId());
        $this->sessionId = null;

        return $this;
    }

    /**
     * Returns the current session ID.
     *
     * @return string
     */
    public function getSessionId()
    {
        if (null === $this->sessionId) {
            throw new LibraryException('This session was closed');
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
        return base64_decode($this->requestValue('screenshot'));
    }

    /**
     * Requests the source code of the current page.
     *
     * @return string The source code of the current page
     */
    public function getSource()
    {
        return $this->requestValue('source');
    }

    /**
     * Requests the title of the current page.
     *
     * @return string The page title
     */
    public function getTitle()
    {
        return $this->requestValue('title');
    }

    /**
     * Method to select an element, using a selector (css, xpath, etc.).
     *
     * @param By $by Indicates how to search for the element
     *
     * @return Element
     *
     * @see By
     */
    public function element(By $by, Element $from = null)
    {
        if (null === $from) {
            $uri = 'element';
        } else {
            $uri = 'element/'.$from->getId().'/element';
        }
        $response = $this->request('POST', $uri, json_encode($by->toArray()));
        $data = json_decode($response->getContent(), true);

        if (!isset($data['value']['ELEMENT'])) {
            throw new LibraryException('Missing key value.ELEMENT');
        }
        $id = $data['value']['ELEMENT'];

        return new Element($this, $id);
    }

    /**
     * Method to select elements, usinga sleector (css, xpath, etc.).
     *
     * @param By $by Indicates how to search for elements
     *
     * @return array An array of elements
     */
    public function elements(By $by, $from = null)
    {
        if (null === $from) {
            $uri = 'elements';
        } else {
            $uri = 'element/'.$from->getId().'/elements';
        }
        $response = $this->request('POST', $uri, json_encode($by->toArray()));
        $data = json_decode($response->getContent(), true);

        if (!isset($data['value'])) {
            throw new LibraryException('Missing key value');
        }

        $elements = array();

        foreach ($data['value'] as $val) {
            $elements[] = new Element($this, $val['ELEMENT']);
        }

        return $elements;
    }

    public function setScriptTimeout($milliseconds)
    {
        $this->request('POST', 'timeouts', json_encode(array('type' => 'script', 'ms' => $milliseconds)));
    }

    /**
     * Set the amount of time, in milliseconds, that asynchronous scripts
     * executed by executeAsync are permitted to run before they are
     * aborted and a TimeoutException occurs.
     *
     * @param int $milliseconds
     */
    public function setAsyncScriptTimeout($milliseconds)
    {
        $this->request('POST', 'timeouts/async_script', json_encode(array('ms' => $milliseconds)));
    }

    public function setPageLoadTimeout($milliseconds)
    {
        $this->request('POST', 'timeouts', json_encode(array('type' => 'page load', 'ms' => $milliseconds)));
    }

    /**
     * Set the amount of time the driver should wait when searching for
     * elements.
     *
     * If this command is never sent, the driver should default to an
     * implicit wait of 0ms.
     *
     * @param int $milliseconds
     */
    public function setImplicitTimeout($milliseconds)
    {
        $this->request('POST', 'timeouts', json_encode(array('type' => 'implicit', 'ms' => $milliseconds)));
    }

    /**
     * Request for browser to close session on object destruction.
     *
     * This will actually make an HTTP call before destruction of object. Be
     * careful when using it.
     *
     * @return Browser fluid interface
     */
    public function closeOnDestruct($value = true)
    {
        $this->closeOnDestruct = $value;

        return $this;
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
    public function requestValue($name)
    {
        $response = $this->client->request('GET', sprintf('/session/%s/%s', $this->sessionId, $name));
        $content = json_decode($response->getContent(), true);

        if (!isset($content['value'])) {
            throw new LibraryException('Malformed response: expected a key "value" in JSON response: '.$response->getContent());
        }

        return $content['value'];
    }

    public function __destruct()
    {
        if ($this->closeOnDestruct) {
            $this->close();
        }
    }
}
