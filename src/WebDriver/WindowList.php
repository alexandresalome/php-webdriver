<?php

/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver;

/**
 * Root entry to manipulate browser.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class WindowList
{
    /**
     * @var Browser
     */
    private $browser;

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * Returns string representation of current window.
     *
     * @return string
     */
    public function getCurrent()
    {
        return $this->browser->requestValue('window_handle');
    }

    /**
     * Returns list of window handles.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->browser->requestValue('window_handles');
    }

    /**
     * @return WindowList
     */
    public function closeCurrent()
    {
        $this->browser->request('DELETE', 'window');

        return $this;
    }

    /**
     * @return WindowList
     */
    public function focus($name)
    {
        $this->browser->request('POST', 'window', json_encode(array('name' => $name)));

        return $this;
    }

    /**
     * @param string $name name of window handler. If ommitted, will be current
     *
     * @return array an array of two elements (width and height)
     */
    public function getSize($name = null)
    {
        if (null === $name) {
            $name = 'current';
        }

        $response = $this->browser->request('GET', 'window/'.urlencode($name).'/size');
        $body = json_decode($response->getContent(), true);

        return array($body['value']['width'], $body['value']['height']);
    }

    /**
     * Changes a window size.
     *
     * @return WindowList
     */
    public function setSize($width, $height, $name = null)
    {
        if (null === $name) {
            $name = 'current';
        }

        $this->browser->request('POST', 'window/'.urlencode($name).'/size', json_encode(array('width' => $width, 'height' => $height)));

        return $this;
    }

    /**
     * @param string $name name of window handler. If ommitted, will be current
     *
     * @return array an array of two elements (x and y)
     */
    public function getPosition($name = 'current')
    {
        $response = $this->browser->request('GET', 'window/'.urlencode($name).'/position');
        $body = json_decode($response->getContent(), true);

        return array($body['value']['x'], $body['value']['y']);
    }

    /**
     * Changes a window position.
     *
     * @return WindowList
     */
    public function setPosition($x, $y, $name = 'current')
    {
        if (null === $name) {
            $name = 'current';
        }

        $this->browser->request('POST', 'window/'.urlencode($name).'/position', json_encode(array('x' => $x, 'y' => $y)));

        return $this;
    }

    /**
     * Maximizes a window.
     *
     * @return WindowList
     */
    public function maximize($name = 'current')
    {
        if (null === $name) {
            $name = 'current';
        }

        $this->browser->request('POST', 'window/'.urlencode($name).'/maximize');

        return $this;
    }
}
